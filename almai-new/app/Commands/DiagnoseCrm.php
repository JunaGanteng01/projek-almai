<?php

namespace App\Commands;

use App\Repositories\CrmChatRepository;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DiagnoseCrm extends BaseCommand
{
    protected $group='CRM';
    protected $name='crm:diagnose';
    protected $description='Memeriksa schema, ringkasan, dan antrean CRM lokal.';

    public function run(array $params)
    {
        $db=\Config\Database::connect();
        foreach(['cs_conversations','cs_messages','crm_status_history','automation_workflows','crm_jobs'] as $table){
            if(!$db->tableExists($table)){CLI::error("Tabel {$table} belum tersedia. Jalankan php spark crm:install");return;}
        }
        $repo=new CrmChatRepository();$summary=$repo->summary();$sessions=$repo->paginate(['limit'=>10]);
        CLI::write('Schema: OK','green');
        CLI::write('Summary: '.json_encode($summary,JSON_UNESCAPED_UNICODE),'white');
        CLI::write('Antrean terdeteksi: '.$sessions['pagination']['total'],'white');
    }
}
