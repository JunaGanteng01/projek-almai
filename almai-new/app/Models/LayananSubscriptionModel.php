<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananSubscriptionModel extends Model
{
    protected $table = 'layanan_subscription';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'type',
        'wpa_id',
        'cwpa_id',
        'name',
        'slug',
        'description',
        'thumbnail',
        'price',
        'original_price',
        'duration_days',
        'benefits',
        'includes',
        'requirements',
        'max_slots',
        'current_slots',
        'schedule_info',
        'is_pro_only',
        'is_featured',
        'specialist',
        'status',
        'referral_wpa_poin',
        'referral_wpa_cash',
        'referral_user_poin',
        'referral_user_cash',
        'referral_distribution_percentage',
        'referral_max_depth',
        'total_sessions',
        'poin_price',
        'fitur_unggulan',
        'layanan_utama',
        'rejection_reason',
        'youtube_tutorials',
        'jenis_modul'
    ];
    protected $useTimestamps = true;

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
        $this->whereIn('status', ['published', 'aktif']);
        if ($type) {
            $this->where('type', $type);
        }
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function getWithWpa($id = null)
    {
        $select = 'layanan_subscription.*, wpa.name as wpa_name, wpa.photo as wpa_photo, wpa.slug as wpa_slug';
        $builder = $this->select($select)
            ->join('wpa', 'wpa.id = layanan_subscription.wpa_id', 'left');

        if ($this->db->fieldExists('cwpa_id', 'layanan_subscription')) {
            $builder->select('cwpa.name as cwpa_name, cwpa.photo as cwpa_photo, cwpa.slug as cwpa_slug')
                ->join('cwpa', 'cwpa.id = layanan_subscription.cwpa_id', 'left');
        }

        if ($id) {
            return $builder->where('layanan_subscription.id', $id)->first();
        }
        return $builder->findAll();
    }
}
