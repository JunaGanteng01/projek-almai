<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWpaIdToLayananTools extends Migration
{
    public function up()
    {
        // Add wpa_id column to layanan_tools
        $fields = [
            'wpa_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true, // Allow NULL since it might be platform tool
                'after'          => 'id' // Position after id
            ],
        ];
        
        // Check if column already exists to avoid errors
        $db = \Config\Database::connect();
        if (!$db->getFieldData('layanan_tools', 'wpa_id')) {
            $this->forge->addColumn('layanan_tools', $fields);
            
            // Add foreign key constraint
            // Notes: We use raw sql here for constraint to be safe or forge
            // Ideally: $this->forge->addForeignKey('wpa_id', 'wpa', 'id', 'SET NULL', 'CASCADE');
            // But addColumn doesn't support adding FK easily in one go with some drivers.
            // Let's execute raw SQL for the FK if needed, but for now just the column is critical.
            // Actually, let's try to add the index and FK properly.
            
            // Add index
            $db->query('ALTER TABLE `layanan_tools` ADD KEY `layanan_tools_wpa_id_foreign` (`wpa_id`)');
            
            // Add FK
            // $db->query('ALTER TABLE `layanan_tools` ADD CONSTRAINT `layanan_tools_wpa_id_foreign` FOREIGN KEY (`wpa_id`) REFERENCES `wpa` (`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        }
    }

    public function down()
    {
        // Remove wpa_id column
        // Check if exists
        $db = \Config\Database::connect();
        if ($db->getFieldData('layanan_tools', 'wpa_id')) {
            // Drop FK first if exists (might fail if name differs, so wrap in try catch logic or ignore)
            // $db->query('ALTER TABLE `layanan_tools` DROP FOREIGN KEY `layanan_tools_wpa_id_foreign`');
            
            $this->forge->dropColumn('layanan_tools', 'wpa_id');
        }
    }
}
