<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCertificatesTable extends Migration
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
            'certificate_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'layanan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'layanan_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'layanan',
            ],
            'layanan_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'user_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'wpa_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'completion_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'issued_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'pdf_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
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
        $this->forge->addKey('certificate_number', false, true); // Unique
        $this->forge->addKey(['user_id', 'layanan_id']);
        $this->forge->createTable('certificates');
    }

    public function down()
    {
        $this->forge->dropTable('certificates');
    }
}
