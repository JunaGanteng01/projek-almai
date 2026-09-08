<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleIdToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'google_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email'
            ]
        ]);
        
        // Add index for faster lookup
        $this->db->query('CREATE INDEX idx_users_google_id ON users(google_id)');
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'google_id');
    }
}
