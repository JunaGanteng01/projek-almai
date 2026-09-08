<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['key' => 'site_name', 'value' => 'ALMAI', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Platform Edukasi Trading Terbaik di Indonesia', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'whatsapp_cs', 'value' => '6285183231800', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'email_cs', 'value' => 'cs@almai.id', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'instagram', 'value' => '@almai.id', 'type' => 'text', 'group' => 'social'],
            ['key' => 'youtube', 'value' => 'https://youtube.com/@almai', 'type' => 'text', 'group' => 'social'],
            ['key' => 'telegram', 'value' => '@almai_official', 'type' => 'text', 'group' => 'social'],
            ['key' => 'poin_per_transaksi', 'value' => '1', 'type' => 'number', 'group' => 'poin'],
            ['key' => 'poin_value', 'value' => '100', 'type' => 'number', 'group' => 'poin'],
            ['key' => 'min_redeem_poin', 'value' => '100', 'type' => 'number', 'group' => 'poin'],
            ['key' => 'vouchers', 'value' => '{"ALMAI10":{"discount":"10","type":"percent","max_use":"100","used":0,"expiry":"2025-12-31","active":true},"ALMAI20":{"discount":"20","type":"percent","max_use":"50","used":0,"expiry":"2025-06-30","active":true}}', 'type' => 'json', 'group' => 'voucher'],
        ];

        foreach ($data as $row) {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('settings')->insert($row);
        }
    }
}
