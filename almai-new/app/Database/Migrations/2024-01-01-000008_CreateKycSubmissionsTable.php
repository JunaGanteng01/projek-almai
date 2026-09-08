<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKycSubmissionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'full_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'nik' => ['type' => 'VARCHAR', 'constraint' => 16],
            'birth_place' => ['type' => 'VARCHAR', 'constraint' => 100],
            'birth_date' => ['type' => 'DATE'],
            'gender' => ['type' => 'ENUM', 'constraint' => ['male', 'female']],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20],
            'address' => ['type' => 'TEXT'],
            'province' => ['type' => 'VARCHAR', 'constraint' => 100],
            'city' => ['type' => 'VARCHAR', 'constraint' => 100],
            'postal_code' => ['type' => 'VARCHAR', 'constraint' => 10],
            'ktp_photo' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bank_name' => ['type' => 'VARCHAR', 'constraint' => 50],
            'bank_branch' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'account_number' => ['type' => 'VARCHAR', 'constraint' => 50],
            'account_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'experience' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'investment_goal' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'risk_profile' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->createTable('kyc_submissions');
    }

    public function down()
    {
        $this->forge->dropTable('kyc_submissions');
    }
}
