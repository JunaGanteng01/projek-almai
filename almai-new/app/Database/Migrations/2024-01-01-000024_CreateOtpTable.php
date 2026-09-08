<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOtpTable extends Migration
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
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'otp' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['registration', 'login', 'password_reset'],
                'default' => 'registration',
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'used' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('email');
        $this->forge->addKey(['email', 'otp']);
        $this->forge->createTable('otp_codes');
    }

    public function down()
    {
        $this->forge->dropTable('otp_codes');
    }
}
