<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWpaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'photo' => ['type' => 'VARCHAR', 'constraint' => 500],
            'specialty' => ['type' => 'VARCHAR', 'constraint' => 100],
            'experience' => ['type' => 'VARCHAR', 'constraint' => 50],
            'rating' => ['type' => 'DECIMAL', 'constraint' => '2,1', 'default' => 0],
            'total_classes' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'bio' => ['type' => 'TEXT'],
            'instagram' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'certifications' => ['type' => 'JSON', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('wpa');
    }

    public function down()
    {
        $this->forge->dropTable('wpa');
    }
}
