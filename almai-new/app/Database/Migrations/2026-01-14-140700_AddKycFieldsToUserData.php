<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKycFieldsToUserData extends Migration
{
    public function up()
    {
        $fields = [
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'user_id'
            ],
            'birth_place' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'birth_place_and_date'
            ],
            'birth_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'birth_place'
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['male', 'female'],
                'null' => true,
                'after' => 'birth_date'
            ],
            'province' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'address'
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'province'
            ],
            'postal_code' => [ // Maps to 'postal_code' in KYC form, user_data had 'company_postcode'
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'city'
            ],
        ];

        // Check if columns exist before adding
        foreach ($fields as $column => $definition) {
            if (!$this->db->fieldExists($column, 'user_data')) {
                $this->forge->addColumn('user_data', [$column => $definition]);
            }
        }
    }

    public function down()
    {
        $columns = ['full_name', 'birth_place', 'birth_date', 'gender', 'province', 'city', 'postal_code'];
        foreach ($columns as $column) {
            if ($this->db->fieldExists($column, 'user_data')) {
                $this->forge->dropColumn('user_data', $column);
            }
        }
    }
}
