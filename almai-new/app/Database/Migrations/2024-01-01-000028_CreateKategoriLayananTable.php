<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriLayananTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 100],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('kategori_layanan');

        // Insert default categories
        $this->db->table('kategori_layanan')->insertBatch([
            ['name' => 'Advokasi', 'slug' => 'advokasi', 'icon' => 'fas fa-gavel', 'description' => 'Layanan pendampingan dan edukasi trading', 'sort_order' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Expert Advisor', 'slug' => 'expert-advisor', 'icon' => 'fas fa-robot', 'description' => 'Robot trading otomatis', 'sort_order' => 2, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Almai Ultimate', 'slug' => 'almai-ultimate', 'icon' => 'fas fa-crown', 'description' => 'Layanan premium eksklusif', 'sort_order' => 3, 'created_at' => date('Y-m-d H:i:s')],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('kategori_layanan');
    }
}
