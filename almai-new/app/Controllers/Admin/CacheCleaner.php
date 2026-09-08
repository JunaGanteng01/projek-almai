<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class CacheCleaner extends BaseController
{
    public function index()
    {
        // Only allow admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login');
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
        
        return "Cache Cleared! Deleted $deleted of $count files from writable/cache. <br> <a href='" . base_url('admin/dashboard') . "'>Back to Dashboard</a>";
    }
}
