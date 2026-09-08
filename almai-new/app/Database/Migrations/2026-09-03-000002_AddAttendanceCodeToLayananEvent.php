<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAttendanceCodeToLayananEvent extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('attendance_code', 'layanan_event')) {
            $this->forge->addColumn('layanan_event', [
                'attendance_code' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'after' => 'attendance_id',
                ],
            ]);
        }

        $sources = [
            'seminar_fgd' => 'seminar_fgd',
            'pelatihan_simulasi' => 'pelatihan_simulasi',
            'signals' => 'signals',
            'konsultasi' => 'konsultasi',
            'expert_advisor' => 'expert_advisor',
            'kegiatan_lainnya' => 'kegiatan_lainnya',
        ];

        foreach ($sources as $type => $table) {
            if (!$this->db->tableExists($table) || !$this->db->fieldExists('kode_qr', $table)) {
                continue;
            }

            $this->db->query(
                "UPDATE layanan_event le "
                . "INNER JOIN {$table} activity ON activity.id = le.attendance_id "
                . "SET le.attendance_code = activity.kode_qr "
                . "WHERE le.attendance_type = ? AND le.attendance_code IS NULL",
                [$type]
            );
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('attendance_code', 'layanan_event')) {
            $this->forge->dropColumn('layanan_event', 'attendance_code');
        }
    }
}
