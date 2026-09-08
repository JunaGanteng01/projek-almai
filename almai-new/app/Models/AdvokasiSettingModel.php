<?php

namespace App\Models;

use CodeIgniter\Model;

class AdvokasiSettingModel extends Model
{
    protected $table = 'advokasi_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'price',
        'description',
        'thumbnail',
        'materials',
        'ea_file',
        'ea_duration',
        'zoom_link',
        'zoom_meeting_id',
        'zoom_password',
        'canva_embed_url',
        'software',
        'ea_list',
        'guide_file',
    ];
    protected $useTimestamps = true;

    /**
     * Get settings (only one row)
     */
    public function getSettings()
    {
        $settings = $this->find(1);
        if (!$settings) {
            // Return default or empty structure
            return [
                'id' => 1,
                'price' => 0,
                'description' => 'Program Advokasi Trader Basic (7 Hari)',
                'thumbnail' => null,
                'materials' => '[]',
                'ea_file' => null,
                'ea_duration' => 30,
                'zoom_link' => null,
                'zoom_meeting_id' => null,
                'zoom_password' => null,
                'canva_embed_url' => null,
            ];
        }

        // Decode materials and software if they are JSON
        if (isset($settings['materials']) && !is_array($settings['materials'])) {
            $settings['materials'] = json_decode($settings['materials'], true) ?: [];
        }

        if (isset($settings['software']) && !is_array($settings['software'])) {
            $settings['software'] = json_decode($settings['software'], true) ?: [];
        }

        if (isset($settings['ea_list']) && !is_array($settings['ea_list'])) {
            $settings['ea_list'] = json_decode($settings['ea_list'], true) ?: [];
        }

        return $settings;
    }
}
