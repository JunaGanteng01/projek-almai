<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLayananSubscriptionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'type' => ['type' => 'ENUM', 'constraint' => ['vip_member', 'private_konsultan', 'pendampingan', 'profirm'], 'default' => 'vip_member'],
            'wpa_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true], // for private konsultan
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'thumbnail' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'duration_days' => ['type' => 'INT', 'constraint' => 11, 'default' => 30], // subscription duration
            'benefits' => ['type' => 'JSON', 'null' => true],
            'includes' => ['type' => 'JSON', 'null' => true],
            'requirements' => ['type' => 'JSON', 'null' => true],
            'max_slots' => ['type' => 'INT', 'constraint' => 11, 'default' => 0], // 0 = unlimited
            'current_slots' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'schedule_info' => ['type' => 'TEXT', 'null' => true], // for private konsultan
            'is_pro_only' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'is_featured' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['draft', 'published', 'archived'], 'default' => 'draft'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('type');
        $this->forge->addKey('wpa_id');
        $this->forge->addKey('status');
        $this->forge->createTable('layanan_subscription');
    }

    public function down()
    {
        $this->forge->dropTable('layanan_subscription');
    }
}
