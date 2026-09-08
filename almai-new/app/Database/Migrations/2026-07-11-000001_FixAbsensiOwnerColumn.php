<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixAbsensiOwnerColumn extends Migration
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
            if ($this->db->fieldExists('created_by_admin_id', $table)) {
                // Admin user IDs are BIGINT; int unsigned overflowed to 4294967295.
                $this->forge->modifyColumn($table, [
                    'created_by_admin_id' => [
                        'type'       => 'BIGINT',
                        'unsigned'   => true,
                        'null'       => true,
                    ],
                ]);
                // Fix legacy overflowed rows (UINT_MAX) -> NULL (unowned/legacy, editable by any admin).
                $this->db->table($table)
                    ->where('created_by_admin_id', 4294967295)
                    ->update(['created_by_admin_id' => null]);
            }
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            if ($this->db->fieldExists('created_by_admin_id', $table)) {
                $this->forge->modifyColumn($table, [
                    'created_by_admin_id' => [
                        'type'     => 'INT',
                        'unsigned' => true,
                        'null'     => true,
                    ],
                ]);
            }
        }
    }
}
