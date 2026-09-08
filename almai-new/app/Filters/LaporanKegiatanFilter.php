<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LaporanKegiatanFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            $session->set('redirectAfterLogin', current_url());
            return redirect()->to('/laporan-kegiatan/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if ($session->get('level_id') != \App\Models\LevelModel::LEVEL_PARTNERSHIP && $session->get('level_id') != \App\Models\LevelModel::LEVEL_SUPER_ADMIN) {
            return redirect()->to('/laporan-kegiatan/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
