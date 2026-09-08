<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCurrencyToCustomerInvoices extends Migration
{
    public function up()
    {
        $this->forge->addColumn('customer_invoices', [
            'currency' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'IDR',
                'after'      => 'product_name',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('customer_invoices', 'currency');
    }
}
