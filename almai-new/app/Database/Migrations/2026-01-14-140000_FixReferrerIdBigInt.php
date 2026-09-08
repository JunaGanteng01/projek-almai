<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixReferrerIdBigInt extends Migration
{
    public function up()
    {
        // Update transaksi table
        $this->forge->addColumn('transaksi', [
            'referrer_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
                'default' => null,
            ],
        ]);
        // Note: CodeIgniter's modifyColumn isn't always reliable across drivers, 
        // but since we are fixing a schema, using raw query or modifyColumn is fine.
        // Let's use raw SQL for certainty as tested in the fix script.
        
        $this->db->query("ALTER TABLE transaksi MODIFY COLUMN referrer_id BIGINT UNSIGNED NULL DEFAULT NULL");
        $this->db->query("ALTER TABLE points MODIFY COLUMN user_id BIGINT UNSIGNED NULL DEFAULT NULL");
    }

    public function down()
    {
        // Reverting this is risky if IDs are already large, but strictly speaking:
        // $this->db->query("ALTER TABLE transaksi MODIFY COLUMN referrer_id INT(11) UNSIGNED NULL DEFAULT NULL");
        // We will leave down() empty or minimal as shrinking ID columns causes data loss.
    }
}
