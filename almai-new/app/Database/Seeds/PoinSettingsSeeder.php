<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PoinSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // Komisi referral dari pendaftaran (untuk referrer)
            [
                'key' => 'poin_referral_registration',
                'value' => '1000',
                'type' => 'number',
                'group' => 'poin'
            ],
            // Bonus untuk user baru yang daftar pakai kode referral
            [
                'key' => 'poin_new_user_referral_bonus',
                'value' => '500',
                'type' => 'number',
                'group' => 'poin'
            ],
            // Komisi referral dari pembelian (persentase)
            [
                'key' => 'poin_referral_purchase_percent',
                'value' => '5',
                'type' => 'number',
                'group' => 'poin'
            ],
            // Komisi WPA dari pembelian kelas/tools (persentase)
            [
                'key' => 'poin_wpa_commission_percent',
                'value' => '10',
                'type' => 'number',
                'group' => 'poin'
            ],
            // Nilai tukar poin ke rupiah (1 poin = x rupiah)
            [
                'key' => 'poin_to_rupiah',
                'value' => '100',
                'type' => 'number',
                'group' => 'poin'
            ],
            // Minimum poin untuk redeem
            [
                'key' => 'poin_minimum_redeem',
                'value' => '10000',
                'type' => 'number',
                'group' => 'poin'
            ],
        ];

        $db = \Config\Database::connect();
        $builder = $db->table('settings');

        foreach ($settings as $setting) {
            $existing = $builder->where('key', $setting['key'])->get()->getRow();
            if (!$existing) {
                $setting['created_at'] = date('Y-m-d H:i:s');
                $setting['updated_at'] = date('Y-m-d H:i:s');
                $builder->insert($setting);
            }
        }
    }
}
