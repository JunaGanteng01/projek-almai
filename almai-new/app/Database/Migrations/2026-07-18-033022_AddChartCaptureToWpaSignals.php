<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddChartCaptureToWpaSignals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('wpa_signals', [
            'chart_capture' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'ai_review'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('wpa_signals', 'chart_capture');
    }
}
