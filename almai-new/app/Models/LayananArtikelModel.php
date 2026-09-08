<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananArtikelModel extends Model
{
    protected $table = 'layanan_artikel';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'wpa_id',
        'cwpa_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'file_path',
        'file_type',
        'poin_price',
        'is_pro_only',
        'is_featured',
        'jenis_modul',
        'download_count',
        'view_count',
        'specialist',
        'status',
        'published_at',
        'referral_wpa_poin',
        'referral_wpa_cash',
        'referral_user_poin',
        'referral_user_cash',
        'referral_distribution_percentage',
        'referral_max_depth',
        'fitur_unggulan',
        'layanan_utama',
        'rejection_reason',
        'youtube_tutorials'
    ];
    protected $useTimestamps = true;

    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['title']) && empty($data['data']['slug'])) {
            $slug = url_title($data['data']['title'], '-', true);
            $existing = $this->where('slug', $slug)->first();
            if ($existing && (!isset($data['id']) || $existing['id'] != $data['id'])) {
                $slug .= '-' . time();
            }
            $data['data']['slug'] = $slug;
        }
        return $data;
    }

    public function getPublished()
    {
        return $this->whereIn('status', ['published', 'aktif'])->orderBy('published_at', 'DESC')->findAll();
    }

    public function getWithWpa($id = null)
    {
        $select = 'layanan_artikel.*, wpa.name as wpa_name, wpa.photo as wpa_photo, wpa.slug as wpa_slug';
        $builder = $this->select($select)
            ->join('wpa', 'wpa.id = layanan_artikel.wpa_id', 'left');

        if ($this->db->fieldExists('cwpa_id', 'layanan_artikel')) {
            $builder->select('cwpa.name as cwpa_name, cwpa.photo as cwpa_photo, cwpa.slug as cwpa_slug')
                ->join('cwpa', 'cwpa.id = layanan_artikel.cwpa_id', 'left');
        }

        if ($id) {
            return $builder->where('layanan_artikel.id', $id)->first();
        }
        return $builder->findAll();
    }
}
