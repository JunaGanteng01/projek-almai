<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePoinPackagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'poin_amount' => ['type' => 'INT', 'constraint' => 11],
            'price' => ['type' => 'BIGINT', 'constraint' => 20],
            'bonus_poin' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_popular' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('poin_packages');
    }

    public function down()
    {
        $this->forge->dropTable('poin_packages');
    }
}
