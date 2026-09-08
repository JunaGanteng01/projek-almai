<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRequiresActivationCodeToOtherLayananTables extends Migration
{
    public function up()
    {
        $tables = ['layanan_tools', 'layanan_subscription', 'layanan_artikel', 'layanan_event'];
        $fields = [
            'requires_activation_code' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'status',
                'comment'    => '1 if service requires activation code upon purchase, 0 otherwise'
            ],
        ];

        foreach ($tables as $table) {
            $this->forge->addColumn($table, $fields);
        }
    }

    public function down()
    {
        $tables = ['layanan_tools', 'layanan_subscription', 'layanan_artikel', 'layanan_event'];
        foreach ($tables as $table) {
            $this->forge->dropColumn($table, 'requires_activation_code');
        }
    }
}
