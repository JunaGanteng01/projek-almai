<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFilesToLayanan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('layanan', [
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'thumbnail',
            ],
            'file_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'pdf, zip, exe, etc',
                'after' => 'file_path',
            ],
            'event_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'file_type',
            ],
            'event_link' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
                'comment' => 'Zoom/Meet link for webinar',
                'after' => 'event_date',
            ],
            'download_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'event_link',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('layanan', ['file_path', 'file_type', 'event_date', 'event_link', 'download_count']);
    }
}
