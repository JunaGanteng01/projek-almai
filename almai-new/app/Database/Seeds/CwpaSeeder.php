<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CwpaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'       => 'Andi Pratama',
                'university' => 'Universitas Indonesia',
                'batch'      => 'Batch 1 2024',
                'status'     => 'active',
                'photo'      => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Siti Nurhaliza',
                'university' => 'Institut Teknologi Bandung',
                'batch'      => 'Batch 1 2024',
                'status'     => 'active',
                'photo'      => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Budi Santoso',
                'university' => 'Universitas Gadjah Mada',
                'batch'      => 'Batch 2 2024',
                'status'     => 'active',
                'photo'      => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Dewi Sartika',
                'university' => 'Universitas Airlangga',
                'batch'      => 'Batch 2 2024',
                'status'     => 'active',
                'photo'      => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Reza Rahardian',
                'university' => 'Universitas Diponegoro',
                'batch'      => 'Batch 3 2024',
                'status'     => 'active',
                'photo'      => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Putri Indah',
                'university' => 'Universitas Brawijaya',
                'batch'      => 'Batch 3 2024',
                'status'     => 'active',
                'photo'      => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Kevin Sanjaya',
                'university' => 'Universitas Padjadjaran',
                'batch'      => 'Batch 4 2025',
                'status'     => 'active',
                'photo'      => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Marcus Gideon',
                'university' => 'Binus University',
                'batch'      => 'Batch 4 2025',
                'status'     => 'active',
                'photo'      => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder
        $this->db->table('cwpa')->insertBatch($data);
    }
}
