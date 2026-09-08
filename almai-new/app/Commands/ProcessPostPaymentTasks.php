<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ProcessPostPaymentTasks extends BaseCommand
{
    protected $group       = 'Payment';
    protected $name        = 'payment:process-tasks';
    protected $description = 'Process post-payment tasks (referral, commission, email, etc) that were skipped in webhook callback to avoid timeout';
    protected $usage       = 'payment:process-tasks [transaksi_id]';
    protected $arguments   = [
        'transaksi_id' => 'Optional: Specific transaction ID to process. If not provided, processes all pending transactions.',
    ];

    public function run(array $params = [])
    {
        $transaksiId = $params[0] ?? null;

        CLI::write('Processing post-payment tasks...', 'green');

        try {
            $checkout = new \App\Controllers\Checkout();
            $checkout->processPostPaymentTasks($transaksiId);

            CLI::write('Post-payment tasks completed successfully!', 'green');
            return 0;
        } catch (\Exception $e) {
            CLI::write('Error: ' . $e->getMessage(), 'red');
            return 1;
        }
    }
}
