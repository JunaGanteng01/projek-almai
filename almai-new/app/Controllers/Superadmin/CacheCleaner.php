<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\LevelModel;

class CacheCleaner extends BaseController
{
    public function index()
    {
        // Only allow Super Admin
        if (LevelModel::resolveLevelId(session()) !== LevelModel::LEVEL_SUPER_ADMIN) {
            return redirect()->to('/superadmin/dashboard')->with('error', 'Akses ditolak.');
        }

        $cachePath = WRITEPATH . 'cache';
        
        // Count files before
        $files = glob($cachePath . '/*');
        $count = count($files);
        
        $deleted = 0;
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== 'index.html' && basename($file) !== '.htaccess') {
                if (unlink($file)) {
                    $deleted++;
                }
            }
        }
        
        return "Cache Cleared! Deleted $deleted of $count files from writable/cache. <br> <a href='" . base_url('superadmin/dashboard') . "'>Back to Dashboard</a>";
    }
}
