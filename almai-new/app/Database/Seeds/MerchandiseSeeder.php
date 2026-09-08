<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MerchandiseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Polo ALMAI Official',
                'description' => 'Polo shirt premium official ALMAI',
                'image' => '/images/merchandise/Polo Almai Official.png',
                'points_required' => 800,
                'stock' => 50,
                'unlimited_stock' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Mug ALMAI',
                'description' => 'Mug keramik eksklusif ALMAI',
                'image' => '/images/merchandise/Mug (Alma ,Aiwe ,Bidbox).jpg',
                'points_required' => 300,
                'stock' => 100,
                'unlimited_stock' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Topi ALMAI',
                'description' => 'Topi snapback limited edition',
                'image' => '/images/merchandise/Topi  (Alma ,Aiwe ,Bidbox).jpg',
                'points_required' => 400,
                'stock' => 30,
                'unlimited_stock' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Totebag ALMAI',
                'description' => 'Totebag kanvas premium',
                'image' => '/images/merchandise/Totebag  (Alma ,Aiwe ,Bidbox).jpg',
                'points_required' => 350,
                'stock' => 75,
                'unlimited_stock' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Tumbler ALMAI',
                'description' => 'Tumbler stainless steel',
                'image' => '/images/merchandise/Tumbler (Alma ,Aiwe ,Bidbox).jpg',
                'points_required' => 500,
                'stock' => 60,
                'unlimited_stock' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Lanyard ALMAI',
                'description' => 'Lanyard ID card holder',
                'image' => '/images/merchandise/Landyard  (Alma ,Aiwe ,Bidbox).jpg',
                'points_required' => 150,
                'stock' => 200,
                'unlimited_stock' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Payung ALMAI',
                'description' => 'Payung lipat premium',
                'image' => '/images/merchandise/Payung Almai.jpg',
                'points_required' => 450,
                'stock' => 40,
                'unlimited_stock' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Voucher Rp 300.000',
                'description' => 'Voucher diskon kelas senilai Rp 300.000',
                'image' => '/images/merchandise/Voucher 300 Ribu.jpg',
                'points_required' => 250,
                'stock' => 0,
                'unlimited_stock' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Voucher Rp 800.000',
                'description' => 'Voucher diskon kelas senilai Rp 800.000',
                'image' => '/images/merchandise/Voucher 800 Ribu.jpg',
                'points_required' => 600,
                'stock' => 0,
                'unlimited_stock' => 1,
                'status' => 'active',
            ],
        ];

        foreach ($data as $row) {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('merchandise')->insert($row);
        }
    }
}
