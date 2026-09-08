<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMerchandiseRedemptionsTable extends Migration
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
            'merchandise_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'total_points' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'shipped', 'completed'],
                'default' => 'pending',
            ],
            'shipping_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'shipping_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'admin_notes' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('merchandise_id');
        $this->forge->addKey('status');
        $this->forge->createTable('merchandise_redemptions');
    }

    public function down()
    {
        $this->forge->dropTable('merchandise_redemptions');
    }
}
