<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $this->db->table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@almai.id',
            'phone' => '085156789700',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Create demo user
        $this->db->table('users')->insert([
            'name' => 'Demo User',
            'email' => 'user@almai.id',
            'phone' => '081234567890',
            'password' => password_hash('user123', PASSWORD_DEFAULT),
            'role' => 'user',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Run WPA seeder first
        $this->call('WpaSeeder');

        // Create WPA user accounts linked to WPA profiles
        $wpaData = [
            ['name' => 'Alit Widiastika', 'email' => 'alit@almai.id', 'wpa_id' => 1],
            ['name' => 'Aries Yuangga', 'email' => 'aries@almai.id', 'wpa_id' => 2],
            ['name' => 'Erick Perdana', 'email' => 'erick@almai.id', 'wpa_id' => 3],
            ['name' => 'Dewa Sugiarta', 'email' => 'dewa@almai.id', 'wpa_id' => 4],
            ['name' => 'Eka Chandra', 'email' => 'eka@almai.id', 'wpa_id' => 5],
        ];

        foreach ($wpaData as $wpa) {
            // Create user account for WPA
            $this->db->table('users')->insert([
                'name' => $wpa['name'],
                'email' => $wpa['email'],
                'phone' => '08' . rand(1000000000, 9999999999),
                'password' => password_hash('wpa123', PASSWORD_DEFAULT),
                'role' => 'wpa',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Get the user ID
            $userId = $this->db->insertID();

            // Update WPA profile with user_id
            $this->db->table('wpa')->where('id', $wpa['wpa_id'])->update(['user_id' => $userId]);
        }

        // Run other seeders
        $this->call('KelasSeeder');
        $this->call('ArtikelSeeder');
        $this->call('SettingSeeder');
        $this->call('TransaksiSeeder');
        $this->call('PoinSeeder');
        $this->call('LayananSeeder');
    }
}
