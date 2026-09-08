<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PoinSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id' => 3,
                'type' => 'bonus',
                'amount' => 100,
                'description' => 'Bonus pendaftaran akun baru',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
            ],
            [
                'user_id' => 3,
                'type' => 'earn',
                'amount' => 180,
                'description' => 'Poin dari pembelian Gold Trading Masterclass',
                'reference_type' => 'transaksi',
                'reference_id' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'user_id' => 3,
                'type' => 'redeem',
                'amount' => 50,
                'description' => 'Redeem poin untuk diskon',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
        ];

        foreach ($data as $row) {
            $this->db->table('poin')->insert($row);
        }
    }
}
