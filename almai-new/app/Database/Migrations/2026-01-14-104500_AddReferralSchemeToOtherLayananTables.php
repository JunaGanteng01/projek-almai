<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReferralSchemeToOtherLayananTables extends Migration
{
    public function up()
    {
        $fields = [
            'referral_wpa_poin' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'referral_wpa_cash' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0
            ],
            'referral_user_poin' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0
            ],
            'referral_user_cash' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0
            ],
            'referral_distribution_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 50.00
            ],
            'referral_max_depth' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 8
            ],
        ];

        $tables = [
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
            'layanan_artikel',
            'layanan_event',
            'layanan_tools',
            'layanan_subscription'
        ];

        $columns = [
            'referral_wpa_poin',
            'referral_wpa_cash',
            'referral_user_poin',
            'referral_user_cash',
            'referral_distribution_percentage',
            'referral_max_depth'
        ];

        foreach ($tables as $table) {
            $this->forge->dropColumn($table, $columns);
        }
    }
}
