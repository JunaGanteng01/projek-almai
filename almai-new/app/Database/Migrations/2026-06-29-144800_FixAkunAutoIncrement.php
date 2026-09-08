<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixAkunAutoIncrement extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        // Coba jadikan Primary Key terlebih dahulu (Abaikan jika error / sudah jadi PK)
        try {
            $db->query("ALTER TABLE `akun` ADD PRIMARY KEY (`id`);");
        } catch (\Exception $e) {}

        $sql = "ALTER TABLE `akun` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;";
        
        try {
            $db->query($sql);
        } catch (\Exception $e) {
            log_message('error', 'Migration FixAkunAutoIncrement failed: ' . $e->getMessage());
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $sql = "ALTER TABLE `akun` MODIFY `id` INT(11) NOT NULL;";
        
        try {
            $db->query($sql);
        } catch (\Exception $e) {
            log_message('error', 'Migration FixAkunAutoIncrement rollback failed: ' . $e->getMessage());
        }
    }
}
