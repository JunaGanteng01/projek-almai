<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixDangerousCascadeConstraints extends Migration
{
    public function up()
    {
        // Disable foreign key checks temporarily
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        
        // 1. Fix users table - change CASCADE to RESTRICT for level_id
        // Drop existing foreign key
        $this->forge->dropForeignKey('users', 'users_level_id_foreign');
        
        // Add new foreign key with RESTRICT instead of CASCADE
        $this->forge->addForeignKey('level_id', 'levels', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->processIndexes('users');
        
        // 2. Fix user_data table - change CASCADE to RESTRICT for user_id
        // Drop existing foreign key
        $this->forge->dropForeignKey('user_data', 'user_data_user_id_foreign');
        
        // Add new foreign key with RESTRICT instead of CASCADE
        $this->forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->processIndexes('user_data');
        
        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down()
    {
        // Disable foreign key checks temporarily
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        
        // Revert to original CASCADE constraints
        
        // 1. Revert users table
        $this->forge->dropForeignKey('users', 'users_level_id_foreign');
        $this->forge->addForeignKey('level_id', 'levels', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->processIndexes('users');
        
        // 2. Revert user_data table
        $this->forge->dropForeignKey('user_data', 'user_data_user_id_foreign');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->processIndexes('user_data');
        
        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}
