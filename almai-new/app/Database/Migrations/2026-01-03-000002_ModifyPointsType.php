<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyPointsType extends Migration
{
    public function up()
    {
        // Modify 'type' column to be VARCHAR(50) to allow more flexibility
        // and support types like 'earn', 'redeem', 'bonus', etc.
        $this->forge->modifyColumn('points', [
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'regular',
            ],
        ]);
    }

    public function down()
    {
        // Revert back to ENUM if needed (warning: data loss if values don't match)
        $this->forge->modifyColumn('points', [
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['regular', 'rupiah', 'bonus_upgrade_pro', 'bonus_referral_upgrade_pro', 'bonus_review'],
                'default' => 'regular',
            ],
        ]);
    }
}
