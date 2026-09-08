<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePointsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'point' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['regular', 'rupiah', 'bonus_upgrade_pro', 'bonus_referral_upgrade_pro', 'bonus_review'],
                'default' => 'regular',
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'pointable_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'pointable_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'purchaser_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('points');
    }

    public function down()
    {
        $this->forge->dropTable('points');
    }
}
