<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLayananToolsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'type' => ['type' => 'ENUM', 'constraint' => ['toolkit', 'ea'], 'default' => 'toolkit'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'thumbnail' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true], // zip, exe, ex4, ex5
            'version' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'changelog' => ['type' => 'TEXT', 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'compatibility' => ['type' => 'JSON', 'null' => true], // ['MT4', 'MT5']
            'features' => ['type' => 'JSON', 'null' => true],
            'requirements' => ['type' => 'JSON', 'null' => true],
            'documentation_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'is_pro_only' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'is_featured' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'download_count' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['draft', 'published', 'archived'], 'default' => 'draft'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('type');
        $this->forge->addKey('status');
        $this->forge->createTable('layanan_tools');
    }

    public function down()
    {
        $this->forge->dropTable('layanan_tools');
    }
}
