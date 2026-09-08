<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShareTypesToPoin extends Migration
{
    public function up()
    {
        // Modify ENUM to add share_out and share_in types
        $this->db->query("ALTER TABLE poin MODIFY COLUMN type ENUM('earn', 'redeem', 'bonus', 'expired', 'share_out', 'share_in', 'spend') DEFAULT 'earn'");
    }

    public function down()
    {
        // Revert to original ENUM
        $this->db->query("ALTER TABLE poin MODIFY COLUMN type ENUM('earn', 'redeem', 'bonus', 'expired') DEFAULT 'earn'");
    }
}
