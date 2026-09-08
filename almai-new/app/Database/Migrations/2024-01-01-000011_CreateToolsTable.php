<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateToolsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'wpa_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 255],
            'thumbnail' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'original_price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'category' => ['type' => 'VARCHAR', 'constraint' => 100],
            'platform' => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'TEXT', 'null' => true],
            'features' => ['type' => 'TEXT', 'null' => true], // JSON array
            'compatibility' => ['type' => 'TEXT', 'null' => true], // JSON array
            'download_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'documentation_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'rating' => ['type' => 'DECIMAL', 'constraint' => '2,1', 'default' => 0],
            'sales' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('slug');
        $this->forge->addKey('category');
        $this->forge->addKey('status');
        $this->forge->createTable('tools');
    }

    public function down()
    {
        $this->forge->dropTable('tools');
    }
}
