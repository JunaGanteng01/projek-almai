<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateWpaPhaseSeeder extends Seeder
{
    public function run()
    {
        // Update all existing WPA records to have current_phase = 1 (default)
        // You can manually update specific WPAs to different phases as needed
        
        $db = \Config\Database::connect();
        
        // Check if column exists first
        if (!$db->fieldExists('current_phase', 'wpa')) {
            echo "Column 'current_phase' does not exist yet. Please run the SQL file first.\n";
            return;
        }
        
        // Set all WPAs to phase 1 by default
        $db->table('wpa')->update(['current_phase' => 1]);
        
        echo "Updated all WPA records with default phase 1.\n";
        echo "You can manually update specific WPAs to different phases using:\n";
        echo "UPDATE wpa SET current_phase = X WHERE id = Y;\n";
    }
}
