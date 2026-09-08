<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCurrentPhaseToWpaTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('wpa', [
            'current_phase' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
                'default' => 1,
                'comment' => 'Current certification phase (1-8)',
                'after' => 'slug'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('wpa', 'current_phase');
    }
}
