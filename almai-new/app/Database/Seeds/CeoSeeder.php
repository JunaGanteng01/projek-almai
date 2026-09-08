<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CeoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'id' => 10,
            'name' => 'CEO',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Insert ignoring duplicates if id 10 already exists
        $this->db->table('levels')->ignore(true)->insert($data);
    }
}
