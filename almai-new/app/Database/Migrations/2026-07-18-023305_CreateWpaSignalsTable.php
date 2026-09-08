<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWpaSignalsTable extends Migration
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
            'wpa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'pair' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'timeframe' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['BUY', 'SELL'],
            ],
            'entry_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,5',
            ],
            'sl' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,5',
            ],
            'tp1' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,5',
                'null'       => true,
            ],
            'tp2' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,5',
                'null'       => true,
            ],
            'tp3' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,5',
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ai_review' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price_points' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'price_idr' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['ACTIVE', 'CLOSED'],
                'default'    => 'ACTIVE',
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
        // Tambahkan constraint foreign key jika di-support atau disesuaikan
        $this->forge->createTable('wpa_signals');
    }

    public function down()
    {
        $this->forge->dropTable('wpa_signals');
    }
}
