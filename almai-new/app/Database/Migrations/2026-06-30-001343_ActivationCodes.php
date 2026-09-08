<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ActivationCodes extends Migration
{
    public function up()
    {
        // Table: activation_codes
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'unique'     => true,
            ],
            'transaksi_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'product_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'duration_days' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0, // 0 = lifetime
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['available', 'used', 'revoked'],
                'default'    => 'available',
            ],
            'used_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('activation_codes');

        // Table: user_active_services
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'product_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'activation_code_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'activated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true, // null = lifetime
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'expired'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('activation_code_id', 'activation_codes', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('user_active_services');
    }

    public function down()
    {
        $this->forge->dropTable('user_active_services', true);
        $this->forge->dropTable('activation_codes', true);
    }
}
