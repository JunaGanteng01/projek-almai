<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChatLeadsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'whatsapp' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'level' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'budget' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45', // Supports IPv6
                'null'       => true,
            ],
            'service_interested' => [
                 'type' => 'VARCHAR',
                 'constraint' => '255',
                 'null' => true
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
        $this->forge->createTable('chat_leads');
    }

    public function down()
    {
        $this->forge->dropTable('chat_leads');
    }
}
