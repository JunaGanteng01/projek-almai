<?php

namespace App\Commands;

use App\Services\CrmService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ProcessCrmQueue extends BaseCommand
{
    protected $group = 'CRM';
    protected $name = 'crm:process';
    protected $description = 'Memproses reminder SLA dan workflow CRM yang tertunda.';
    protected $usage = 'crm:process';

    public function run(array $params)
    {
        $service = new CrmService();
        $result = [
            'reminders' => $service->processReminders(),
            'jobs' => $service->processJobs(),
        ];
        CLI::write(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 'green');
    }
}
