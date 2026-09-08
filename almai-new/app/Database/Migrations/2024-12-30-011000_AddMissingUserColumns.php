<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingUserColumns extends Migration
{
    public function up()
    {
        // Add missing columns from users.sql to existing users table
        // Only add if column doesn't exist
        
        $fields = [];
        
        // Check each field and add if not exists
        if (!$this->db->fieldExists('affiliator_code', 'users')) {
            $fields['affiliator_code'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'id'
            ];
        }
        
        if (!$this->db->fieldExists('code_referral', 'users')) {
            $fields['code_referral'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'phone'
            ];
        }
        
        if (!$this->db->fieldExists('balance', 'users')) {
            $fields['balance'] = [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'code_referral'
            ];
        }
        
        if (!$this->db->fieldExists('level_id', 'users')) {
            $fields['level_id'] = [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => false,
                'default' => 1,
                'after' => 'balance'
            ];
        }
        
        if (!$this->db->fieldExists('crm_role', 'users')) {
            $fields['crm_role'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'level_id'
            ];
        }
        
        if (!$this->db->fieldExists('otp_status', 'users')) {
            $fields['otp_status'] = [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'default' => 0,
                'after' => 'crm_role'
            ];
        }
        
        if (!$this->db->fieldExists('promo_link_id', 'users')) {
            $fields['promo_link_id'] = [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
                'after' => 'status'
            ];
        }
        
        if (!$this->db->fieldExists('promo_link_redirected_when_first_login', 'users')) {
            $fields['promo_link_redirected_when_first_login'] = [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'default' => 0,
                'after' => 'promo_link_id'
            ];
        }
        
        if (!$this->db->fieldExists('registration_types', 'users')) {
            $fields['registration_types'] = [
                'type' => 'JSON',
                'null' => true,
                'after' => 'promo_link_redirected_when_first_login'
            ];
        }
        
        // Add fields if any
        if (!empty($fields)) {
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        // Remove columns if needed
        $this->forge->dropColumn('users', [
            'affiliator_code',
            'code_referral', 
            'balance',
            // 'level_id', // Do not drop level_id as it has foreign key and belongs to core table
            'crm_role',
            'otp_status',
            'promo_link_id',
            'promo_link_redirected_when_first_login',
            'registration_types'
        ]);
    }
}
