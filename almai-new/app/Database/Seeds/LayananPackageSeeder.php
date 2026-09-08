<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LayananPackageSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Find Angel Gold Event
        $event = $db->table('layanan_events')->like('title', 'Angel Gold')->get()->getRowArray();

        if ($event) {
            $data = [
                [
                    'layanan_type' => 'webinar',
                    'layanan_id' => $event['id'],
                    'name' => 'Early Bird',
                    'price' => 250000,
                    'original_price' => 459000,
                    'description' => 'Tiket khusus pendaftar awal',
                    'features' => json_encode(['Akses Webinar', 'E-Sertifikat']),
                    'sort_order' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'layanan_type' => 'webinar',
                    'layanan_id' => $event['id'],
                    'name' => 'Regular',
                    'price' => 459000,
                    'original_price' => null,
                    'description' => 'Tiket harga normal',
                    'features' => json_encode(['Akses Webinar', 'E-Sertifikat', 'Rekaman']),
                    'sort_order' => 2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ];

            // Check if exists to avoid dupe
            $exists = $db->table('layanan_prices')
                        ->where('layanan_type', 'webinar')
                        ->where('layanan_id', $event['id'])
                        ->countAllResults();
            
            if ($exists == 0) {
                $db->table('layanan_prices')->insertBatch($data);
                echo "Packages inserted for Angel Gold.\n";
            } else {
                echo "Packages already exist for Angel Gold.\n";
            }
        } else {
            echo "Angel Gold event not found.\n";
        }
    }
}
