<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRegistrationRolesToLayananEvent extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('registration_roles', 'layanan_event')) {
            $this->forge->addColumn('layanan_event', [
                'registration_roles' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'default' => null,
                    'after' => 'is_pro_only',
                    'comment' => 'Comma-separated roles allowed to register; NULL means public',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('registration_roles', 'layanan_event')) {
            $this->forge->dropColumn('layanan_event', 'registration_roles');
        }
    }
}
