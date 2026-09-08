<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLayananArtikelTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'wpa_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 255],
            'excerpt' => ['type' => 'TEXT', 'null' => true],
            'content' => ['type' => 'LONGTEXT', 'null' => true],
            'thumbnail' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true], // pdf, doc
            'poin_price' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_pro_only' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'is_featured' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'download_count' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'view_count' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['draft', 'published', 'archived'], 'default' => 'draft'],
            'published_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('wpa_id');
        $this->forge->addKey('status');
        $this->forge->createTable('layanan_artikel');
    }

    public function down()
    {
        $this->forge->dropTable('layanan_artikel');
    }
}
