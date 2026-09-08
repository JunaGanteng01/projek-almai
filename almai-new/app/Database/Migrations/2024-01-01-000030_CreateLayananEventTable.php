<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLayananEventTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'wpa_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'type' => ['type' => 'ENUM', 'constraint' => ['webinar', 'workshop'], 'default' => 'webinar'],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'thumbnail' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'event_date' => ['type' => 'DATETIME'],
            'event_end_date' => ['type' => 'DATETIME', 'null' => true],
            'location' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // for workshop
            'zoom_link' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true], // for webinar
            'meeting_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'meeting_password' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'max_participants' => ['type' => 'INT', 'constraint' => 11, 'default' => 0], // 0 = unlimited
            'current_participants' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'requires_agreement' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'agreement_text' => ['type' => 'TEXT', 'null' => true],
            'is_pro_only' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_featured' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'materials' => ['type' => 'JSON', 'null' => true], // array of file paths
            'status' => ['type' => 'ENUM', 'constraint' => ['draft', 'upcoming', 'ongoing', 'completed', 'cancelled'], 'default' => 'draft'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('wpa_id');
        $this->forge->addKey('type');
        $this->forge->addKey('event_date');
        $this->forge->addKey('status');
        $this->forge->createTable('layanan_event');
    }

    public function down()
    {
        $this->forge->dropTable('layanan_event');
    }
}
