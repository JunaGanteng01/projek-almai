<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class InstallCrm extends BaseCommand
{
    protected $group = 'CRM';
    protected $name = 'crm:install';
    protected $description = 'Menjalankan hanya migrasi CRM tanpa menjalankan migrasi lama yang tertunda.';

    public function run(array $params)
    {
        $db=\Config\Database::connect();
        if(!$db->tableExists('automation_workflows') || !$db->fieldExists('assigned_to','cs_conversations')){
            $path=APPPATH.'Database/Migrations/2026-08-25-000002_CreateCrmMonitoring.php';
            service('migrations')->force($path,'App');
        }
        if(!$db->fieldExists('active_user_id','cs_conversations')){
            $path=APPPATH.'Database/Migrations/2026-08-25-000003_AddCrmActiveSessionGuard.php';
            service('migrations')->force($path,'App');
        }
        CLI::write('Schema CRM berhasil dipasang dan diverifikasi.', 'green');
    }
}
