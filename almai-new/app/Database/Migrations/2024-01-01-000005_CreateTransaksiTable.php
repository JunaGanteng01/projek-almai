<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransaksiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'invoice_number' => ['type' => 'VARCHAR', 'constraint' => 50],
            'user_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'kelas_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'product_type' => ['type' => 'ENUM', 'constraint' => ['kelas', 'tools', 'other'], 'default' => 'kelas'],
            'product_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'discount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'total' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'payment_method' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'payment_proof' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'paid', 'confirmed', 'cancelled', 'refunded'], 'default' => 'pending'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'referral_code' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'referrer_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'referral_poin' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'paid_at' => ['type' => 'DATETIME', 'null' => true],
            'confirmed_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('invoice_number');
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->createTable('transaksi');
    }

    public function down()
    {
        $this->forge->dropTable('transaksi');
    }
}
