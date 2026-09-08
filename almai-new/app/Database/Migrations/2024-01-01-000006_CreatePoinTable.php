<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePoinTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'type' => ['type' => 'ENUM', 'constraint' => ['earn', 'redeem', 'bonus', 'expired'], 'default' => 'earn'],
            'amount' => ['type' => 'INT', 'constraint' => 11],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255],
            'reference_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'reference_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('type');
        $this->forge->createTable('poin');
    }

    public function down()
    {
        $this->forge->dropTable('poin');
    }
}
