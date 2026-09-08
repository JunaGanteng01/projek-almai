<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWpaCommissionToLayananTables extends Migration
{
    public function up()
    {
        $fields = [
            'wpa_commission_percent' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => null, // null means use global default
                'null' => true,
            ],
        ];

        $tables = [
            'layanan',
            'layanan_artikel',
            'layanan_event',
            'layanan_tools',
            'layanan_subscription'
        ];

        foreach ($tables as $table) {
            foreach ($fields as $column => $attributes) {
                if (!$this->db->fieldExists($column, $table)) {
                    $this->forge->addColumn($table, [$column => $attributes]);
                }
            }
        }
    }

    public function down()
    {
        $tables = [
            'layanan',
            'layanan_artikel',
            'layanan_event',
            'layanan_tools',
            'layanan_subscription'
        ];

        foreach ($tables as $table) {
            $this->forge->dropColumn($table, 'wpa_commission_percent');
        }
    }
}
