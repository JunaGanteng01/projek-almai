<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananToolsModel extends Model
{
    protected $table = 'layanan_tools';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'type',
        'name',
        'slug',
        'description',
        'thumbnail',
        'file_path',
        'file_type',
        'version',
        'changelog',
        'price',
        'original_price',
        'compatibility',
        'features',
        'requirements',
        'documentation_url',
        'is_pro_only',
        'is_featured',
        'download_count',
        'specialist',
        'status',
        'referral_wpa_poin',
        'referral_wpa_cash',
        'referral_user_poin',
        'referral_user_cash',
        'referral_distribution_percentage',
        'referral_max_depth',
        'wpa_id',
        'cwpa_id',
        'is_license_product',
        'license_duration',
        'ea_file_path',
        'poin_price',
        'license_prefix',
        'fitur_unggulan',
        'layanan_utama',
        'rejection_reason',
        'youtube_tutorials',
        'jenis_modul'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['name']) && empty($data['data']['slug'])) {
            $slug = url_title($data['data']['name'], '-', true);
            $existing = $this->where('slug', $slug)->first();
            if ($existing && (!isset($data['id']) || $existing['id'] != $data['id'])) {
                $slug .= '-' . time();
            }
            $data['data']['slug'] = $slug;
        }
        return $data;
    }

    public function getPublished($type = null)
    {
        $select = 'layanan_tools.*, wpa.name as wpa_name, wpa.photo as wpa_photo, wpa.slug as wpa_slug';
        $builder = $this->select($select)
            ->join('wpa', 'wpa.id = layanan_tools.wpa_id', 'left');

        if ($this->db->fieldExists('cwpa_id', 'layanan_tools')) {
            $builder->select('cwpa.name as cwpa_name, cwpa.photo as cwpa_photo, cwpa.slug as cwpa_slug')
                ->join('cwpa', 'cwpa.id = layanan_tools.cwpa_id', 'left');
        }
        $builder->whereIn('layanan_tools.status', ['published', 'aktif']);

        if ($type) {
            $builder->where('layanan_tools.type', $type);
        }

        return $builder->orderBy('layanan_tools.created_at', 'DESC')->findAll();
    }
}
