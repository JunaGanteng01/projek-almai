<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRecurringFieldsToLayananEvent extends Migration
{
    public function up()
    {
        $fields = [
            'is_recurring' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'status'
            ],
            'recurring_frequency' => [
                'type' => 'ENUM',
                'constraint' => ['daily', 'weekly', 'monthly'],
                'null' => true,
                'default' => null,
                'after' => 'is_recurring'
            ],
            'recurring_day' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'default' => null,
                'after' => 'recurring_frequency'
            ],
            'recurring_time' => [
                'type' => 'TIME',
                'null' => true,
                'default' => null,
                'after' => 'recurring_day'
            ],
        ];

        $this->forge->addColumn('layanan_event', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('layanan_event', ['is_recurring', 'recurring_frequency', 'recurring_day', 'recurring_time']);
    }
}
