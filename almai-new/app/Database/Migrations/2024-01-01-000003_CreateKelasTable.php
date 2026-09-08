<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKelasTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'wpa_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 255],
            'thumbnail' => ['type' => 'VARCHAR', 'constraint' => 500],
            'price' => ['type' => 'BIGINT', 'constraint' => 20],
            'original_price' => ['type' => 'BIGINT', 'constraint' => 20],
            'duration' => ['type' => 'VARCHAR', 'constraint' => 50],
            'modules' => ['type' => 'INT', 'constraint' => 11],
            'level' => ['type' => 'ENUM', 'constraint' => ['Beginner', 'Intermediate', 'Advanced']],
            'rating' => ['type' => 'DECIMAL', 'constraint' => '2,1', 'default' => 0],
            'students' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'category' => ['type' => 'VARCHAR', 'constraint' => 100],
            'mode' => ['type' => 'ENUM', 'constraint' => ['Online', 'Offline', 'Hybrid']],
            'location' => ['type' => 'VARCHAR', 'constraint' => 100],
            'type' => ['type' => 'ENUM', 'constraint' => ['recorded', 'live'], 'default' => 'recorded'],
            'zoom_link' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'schedule' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'next_session' => ['type' => 'DATE', 'null' => true],
            'description' => ['type' => 'TEXT'],
            'highlights' => ['type' => 'JSON', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive', 'draft'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addForeignKey('wpa_id', 'wpa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kelas');
    }

    public function down()
    {
        $this->forge->dropTable('kelas');
    }
}
