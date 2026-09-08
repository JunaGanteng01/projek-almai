<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'level_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'default' => 1],
            'affiliate_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'avatar' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive', 'banned'], 'default' => 'active'],
            'referral_code' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'referred_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'pro_expires_at' => ['type' => 'DATETIME', 'null' => true],
            'kyc_status' => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected'], 'null' => true],
            'kyc_submitted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('referral_code');
        $this->forge->addForeignKey('level_id', 'levels', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
