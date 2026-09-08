<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLayananTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'category' => [
                'type' => 'ENUM',
                'constraint' => ['Advokasi', 'Expert Advisor', 'Almai Ultimate'],
                'default' => 'Advokasi',
            ],
            'subcategory' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'thumbnail' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'wpa_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'level' => [
                'type' => 'ENUM',
                'constraint' => ['Beginner', 'Intermediate', 'Advanced', 'All Level'],
                'default' => 'Beginner',
            ],
            'mode' => [
                'type' => 'ENUM',
                'constraint' => ['Online', 'Offline', 'Hybrid'],
                'default' => 'Online',
            ],
            'duration' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'modules' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'Online',
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'original_price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'badge' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'JFX, CFX, etc',
            ],
            'is_premium' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'features' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'includes' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'requirements' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'warning' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'rating' => [
                'type' => 'DECIMAL',
                'constraint' => '3,2',
                'default' => 0,
            ],
            'students' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('slug');
        $this->forge->addKey('category');
        $this->forge->addKey('subcategory');
        $this->forge->addForeignKey('wpa_id', 'wpa', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('layanan');
    }

    public function down()
    {
        $this->forge->dropTable('layanan');
    }
}
