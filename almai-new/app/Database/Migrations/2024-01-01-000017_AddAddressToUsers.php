<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAddressToUsers extends Migration
{
    public function up()
    {
        // Check if column exists first
        if (!$this->db->fieldExists('address', 'users')) {
            $this->forge->addColumn('users', [
                'address' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'phone'
                ]
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('address', 'users')) {
            $this->forge->dropColumn('users', 'address');
        }
    }
}
