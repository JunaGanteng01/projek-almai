<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'invoice_number' => 'INV202401010001',
                'user_id' => 3,
                'kelas_id' => 1,
                'product_type' => 'kelas',
                'product_name' => 'Gold Trading Masterclass',
                'amount' => 18000000,
                'discount' => 0,
                'total' => 18000000,
                'payment_method' => 'Bank Transfer',
                'status' => 'confirmed',
                'paid_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'confirmed_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'invoice_number' => 'INV202401020001',
                'user_id' => 3,
                'kelas_id' => 2,
                'product_type' => 'kelas',
                'product_name' => 'Forex Fundamental Analysis',
                'amount' => 12000000,
                'discount' => 1200000,
                'total' => 10800000,
                'payment_method' => 'Bank Transfer',
                'status' => 'paid',
                'paid_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'invoice_number' => 'INV202401030001',
                'user_id' => 3,
                'kelas_id' => 3,
                'product_type' => 'kelas',
                'product_name' => 'Crypto Trading for Beginners',
                'amount' => 8000000,
                'discount' => 0,
                'total' => 8000000,
                'payment_method' => null,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
        ];

        foreach ($data as $row) {
            $this->db->table('transaksi')->insert($row);
        }
    }
}
