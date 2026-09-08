<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRegistrationRolesToAttendanceActivities extends Migration
{
    private array $tables = [
        'seminar_fgd',
        'pelatihan_simulasi',
        'signals',
        'konsultasi',
        'expert_advisor',
        'kegiatan_lainnya',
    ];

    public function up()
    {
        foreach ($this->tables as $table) {
            if ($this->db->tableExists($table) && !$this->db->fieldExists('registration_roles', $table)) {
                $this->forge->addColumn($table, [
                    'registration_roles' => [
                        'type' => 'VARCHAR',
                        'constraint' => 100,
                        'null' => true,
                        'default' => null,
                        'comment' => 'Roles allowed to check in; NULL means public',
                    ],
                ]);
            }
        }

        // Restore paid/manually-created Layanan events to their original public behavior.
        if ($this->db->tableExists('layanan_event') && $this->db->fieldExists('registration_roles', 'layanan_event')) {
            $this->db->table('layanan_event')
                ->groupStart()
                    ->where('attendance_type IS NULL')
                    ->orWhere('attendance_id IS NULL')
                ->groupEnd()
                ->update(['registration_roles' => null]);
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            if ($this->db->tableExists($table) && $this->db->fieldExists('registration_roles', $table)) {
                $this->forge->dropColumn($table, 'registration_roles');
            }
        }
    }
}
