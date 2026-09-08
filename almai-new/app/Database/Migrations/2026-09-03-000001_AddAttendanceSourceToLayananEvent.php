<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAttendanceSourceToLayananEvent extends Migration
{
    public function up()
    {
        $columns = [];

        if (!$this->db->fieldExists('attendance_type', 'layanan_event')) {
            $columns['attendance_type'] = [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'id',
            ];
        }

        if (!$this->db->fieldExists('attendance_id', 'layanan_event')) {
            $columns['attendance_id'] = [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'attendance_type',
            ];
        }

        if (!$this->db->fieldExists('is_paid', 'layanan_event')) {
            $columns['is_paid'] = [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'price',
            ];
        }

        if ($columns !== []) {
            $this->forge->addColumn('layanan_event', $columns);
        }

        // Preserve the meaning of existing free events without changing paid rows.
        $freeEvents = $this->db->table('layanan_event')
            ->groupStart()
                ->where('price', 0)
                ->orWhere('price IS NULL', null, false)
            ->groupEnd();

        if ($this->db->fieldExists('poin_price', 'layanan_event')) {
            $freeEvents->groupStart()
                ->where('poin_price', 0)
                ->orWhere('poin_price IS NULL', null, false)
                ->groupEnd();
        }

        $freeEvents->update(['is_paid' => 0]);

        if (!$this->hasAttendanceSourceIndex()) {
            $this->db->query(
                'CREATE UNIQUE INDEX layanan_event_attendance_source_unique '
                . 'ON layanan_event (attendance_type, attendance_id)'
            );
        }
    }

    public function down()
    {
        if ($this->hasAttendanceSourceIndex()) {
            $this->forge->dropKey('layanan_event', 'layanan_event_attendance_source_unique');
        }

        $columns = array_values(array_filter(
            ['attendance_type', 'attendance_id', 'is_paid'],
            fn (string $column): bool => $this->db->fieldExists($column, 'layanan_event')
        ));

        if ($columns !== []) {
            $this->forge->dropColumn('layanan_event', $columns);
        }
    }

    private function hasAttendanceSourceIndex(): bool
    {
        foreach ($this->db->getIndexData('layanan_event') as $name => $index) {
            if ($name === 'layanan_event_attendance_source_unique'
                || ($index->name ?? null) === 'layanan_event_attendance_source_unique') {
                return true;
            }
        }

        return false;
    }
}
