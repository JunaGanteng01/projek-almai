<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSlugToWpa extends Migration
{
    public function up()
    {
        $this->forge->addColumn('wpa', [
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'name',
            ],
        ]);
        
        // Add unique index
        $this->db->query('CREATE UNIQUE INDEX wpa_slug_unique ON wpa(slug)');
        
        // Generate slugs for existing WPA
        $wpaList = $this->db->table('wpa')->get()->getResultArray();
        foreach ($wpaList as $wpa) {
            $slug = $this->generateSlug($wpa['name']);
            $this->db->table('wpa')->where('id', $wpa['id'])->update(['slug' => $slug]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('wpa', 'slug');
    }
    
    private function generateSlug($name)
    {
        // Convert to lowercase
        $slug = strtolower($name);
        // Remove special characters, keep alphanumeric and spaces
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        // Replace spaces with hyphens
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        // Trim hyphens
        $slug = trim($slug, '-');
        return $slug;
    }
}
