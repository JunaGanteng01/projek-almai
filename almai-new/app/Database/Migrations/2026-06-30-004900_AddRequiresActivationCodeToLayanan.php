<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRequiresActivationCodeToLayanan extends Migration
{
    public function up()
    {
        $fields = [
            'requires_activation_code' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'status',
                'comment'    => '1 if service requires activation code upon purchase, 0 otherwise'
            ],
        ];
        $this->forge->addColumn('layanan', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('layanan', 'requires_activation_code');
    }
}
