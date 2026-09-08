<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOriginalPriceToLayananTables extends Migration
{
    public function up()
    {
        $fields = [
            'original_price' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
                'default' => null,
                'after' => 'price'
            ]
        ];

        // Add to layanan_event
        $this->forge->addColumn('layanan_event', $fields);

        // Add to layanan_tools
        $this->forge->addColumn('layanan_tools', $fields);

        // Add to layanan_subscription
        $this->forge->addColumn('layanan_subscription', $fields);
    }

    public function down()
    {
        $fields = ['original_price'];

        $this->forge->dropColumn('layanan_event', $fields);
        $this->forge->dropColumn('layanan_tools', $fields);
        $this->forge->dropColumn('layanan_subscription', $fields);
    }
}
