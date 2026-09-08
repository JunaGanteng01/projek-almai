<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserUnlockedSignalsTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'signal_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // poin, xendit, dll
                'null'       => true,
            ],
            'amount_paid' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
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
        $this->forge->createTable('user_unlocked_signals');
    }

    public function down()
    {
        $this->forge->dropTable('user_unlocked_signals');
    }
}
