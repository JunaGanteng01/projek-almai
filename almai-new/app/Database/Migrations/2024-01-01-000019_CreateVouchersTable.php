<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVouchersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'discount_type' => [
                'type' => 'ENUM',
                'constraint' => ['percentage', 'fixed'],
                'default' => 'percentage',
            ],
            'discount_value' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'min_purchase' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'max_discount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'comment' => 'Max discount for percentage type',
            ],
            'usage_limit' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'null = unlimited',
            ],
            'used_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'per_user_limit' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'product_type' => [
                'type' => 'ENUM',
                'constraint' => ['all', 'kelas', 'tools', 'artikel'],
                'default' => 'all',
            ],
            'product_ids' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of product IDs, null = all products of type',
            ],
            'start_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'end_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
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

        $this->forge->addKey('status');
        $this->forge->createTable('vouchers');

        // Voucher usage tracking
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'voucher_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'transaksi_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'discount_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('voucher_id');
        $this->forge->addKey('user_id');
        $this->forge->createTable('voucher_usages');
    }

    public function down()
    {
        $this->forge->dropTable('voucher_usages');
        $this->forge->dropTable('vouchers');
    }
}