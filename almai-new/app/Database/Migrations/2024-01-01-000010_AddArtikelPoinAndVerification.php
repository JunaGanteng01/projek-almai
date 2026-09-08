<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddArtikelPoinAndVerification extends Migration
{
    public function up()
    {
        // Add poin_price and verification fields to artikel table
        $this->forge->addColumn('artikel', [
            'poin_price' => ['type' => 'INT', 'constraint' => 11, 'default' => 0, 'after' => 'read_time'],
            'is_free' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'poin_price'],
            'verification_status' => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected'], 'default' => 'pending', 'after' => 'status'],
            'rejection_reason' => ['type' => 'TEXT', 'null' => true, 'after' => 'verification_status'],
            'verified_at' => ['type' => 'DATETIME', 'null' => true, 'after' => 'rejection_reason'],
            'verified_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true, 'after' => 'verified_at'],
        ]);

        // Create artikel_purchases table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'artikel_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'poin_spent' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'artikel_id']);
        $this->forge->createTable('artikel_purchases');
    }

    public function down()
    {
        $this->forge->dropColumn('artikel', ['poin_price', 'is_free', 'verification_status', 'rejection_reason', 'verified_at', 'verified_by']);
        $this->forge->dropTable('artikel_purchases');
    }
}
