<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoneToOtps extends Migration
{
    public function up()
    {
        // Add phone column to otp_codes if not exists
        if (!$this->db->fieldExists('phone', 'otp_codes')) {
            $fields = [
                'phone' => [
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'null' => true,
                    'after' => 'email'
                ]
            ];
            $this->forge->addColumn('otp_codes', $fields);
        }

        // Make email nullable if it isn't already (to support phone-only OTPs) - Optional but good practice
        $fields = [
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ]
        ];
        $this->forge->modifyColumn('otp_codes', $fields);
    }

    public function down()
    {
        if ($this->db->fieldExists('phone', 'otp_codes')) {
            $this->forge->dropColumn('otp_codes', 'phone');
        }
    }
}
