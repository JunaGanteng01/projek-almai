<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLicenseFields extends Migration
{
    public function up()
    {
        // Add columns to layanan_tools
        $fields = [
            'is_license_product' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => true,
            ],
            'license_duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => true,
            ],
            'ea_file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];

        // Check columns exist before adding
        $db = \Config\Database::connect();
        
        if ($db->tableExists('layanan_tools')) {
            if (!$db->fieldExists('is_license_product', 'layanan_tools')) {
                $this->forge->addColumn('layanan_tools', $fields);
            }
        }

        if ($db->tableExists('layanan_event')) {
            if (!$db->fieldExists('is_license_product', 'layanan_event')) {
                $this->forge->addColumn('layanan_event', $fields);
            }
        }
        
        // Ensure ea_licenses table has expiration fields
        if ($db->tableExists('ea_licenses')) {
            $licenseFields = [];
            if (!$db->fieldExists('status', 'ea_licenses')) {
                $licenseFields['status'] = ['type' => 'ENUM', 'constraint' => ['active', 'expired', 'suspended'], 'default' => 'active'];
            }
            if (!$db->fieldExists('expires_at', 'ea_licenses')) {
                $licenseFields['expires_at'] = ['type' => 'DATETIME', 'null' => true];
            }
            if (!empty($licenseFields)) {
                $this->forge->addColumn('ea_licenses', $licenseFields);
            }
        }
    }

    public function down()
    {
        // $this->forge->dropColumn('layanan_tools', ['is_license_product', 'license_duration', 'ea_file_path']);
        // $this->forge->dropColumn('layanan_event', ['is_license_product', 'license_duration', 'ea_file_path']);
    }
}
