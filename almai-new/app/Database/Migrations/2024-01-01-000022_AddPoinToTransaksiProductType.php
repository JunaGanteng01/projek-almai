<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPoinToTransaksiProductType extends Migration
{
    public function up()
    {
        // Modify ENUM to add 'poin' type
        $this->db->query("ALTER TABLE transaksi MODIFY COLUMN product_type ENUM('kelas', 'tools', 'other', 'poin', 'artikel') DEFAULT 'kelas'");
    }

    public function down()
    {
        // Revert to original ENUM
        $this->db->query("ALTER TABLE transaksi MODIFY COLUMN product_type ENUM('kelas', 'tools', 'other') DEFAULT 'kelas'");
    }
}
