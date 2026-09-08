<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSlugToCwpaTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('cwpa', [
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'name',
            ],
            'current_phase' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Current phase (1-8)',
                'after' => 'batch',
            ],
        ]);
        
        // Add unique index for slug
        $this->forge->addKey('slug', false, true);
    }

    public function down()
    {
        $this->forge->dropColumn('cwpa', ['slug', 'current_phase']);
    }
}
