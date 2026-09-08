<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCertificateImagesToWpaTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('wpa', [
            'phase_certificates' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Certificate images for each phase (JSON: {1: "url", 2: "url", ...})',
                'after' => 'current_phase'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('wpa', 'phase_certificates');
    }
}
