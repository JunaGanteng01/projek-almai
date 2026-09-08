<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PoinPackageSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => '1000 Poin',
                'poin_amount' => 1000,
                'price' => 100000,
                'bonus_poin' => 0,
                'description' => 'Paket poin dasar',
                'is_popular' => 0,
                'status' => 'active',
            ],
            [
                'name' => '3000 Poin',
                'poin_amount' => 3000,
                'price' => 300000,
                'bonus_poin' => 0,
                'description' => 'Paket poin standar',
                'is_popular' => 0,
                'status' => 'active',
            ],
            [
                'name' => '8000 Poin',
                'poin_amount' => 8000,
                'price' => 800000,
                'bonus_poin' => 500,
                'description' => 'Bonus 500 poin',
                'is_popular' => 1,
                'status' => 'active',
            ],
            [
                'name' => '10000 Poin',
                'poin_amount' => 10000,
                'price' => 1000000,
                'bonus_poin' => 1000,
                'description' => 'Bonus 1000 poin',
                'is_popular' => 0,
                'status' => 'active',
            ],
            [
                'name' => '100000 Poin',
                'poin_amount' => 100000,
                'price' => 8000000,
                'bonus_poin' => 20000,
                'description' => 'Bonus 20000 poin - Best Value!',
                'is_popular' => 0,
                'status' => 'active',
            ],
        ];

        foreach ($data as $row) {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('poin_packages')->insert($row);
        }
    }
}
