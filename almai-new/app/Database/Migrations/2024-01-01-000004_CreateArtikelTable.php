<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateArtikelTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'wpa_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 255],
            'thumbnail' => ['type' => 'VARCHAR', 'constraint' => 500],
            'excerpt' => ['type' => 'TEXT'],
            'content' => ['type' => 'LONGTEXT'],
            'category' => ['type' => 'VARCHAR', 'constraint' => 100],
            'read_time' => ['type' => 'VARCHAR', 'constraint' => 20],
            'status' => ['type' => 'ENUM', 'constraint' => ['published', 'draft'], 'default' => 'draft'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addForeignKey('wpa_id', 'wpa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('artikel');
    }

    public function down()
    {
        $this->forge->dropTable('artikel');
    }
}
