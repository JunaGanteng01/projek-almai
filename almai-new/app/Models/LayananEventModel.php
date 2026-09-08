<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananEventModel extends Model
{
    protected $table = 'layanan_event';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'wpa_id',
        'cwpa_id',
        'type',
        'title',
        'slug',
        'description',
        'thumbnail',
        'price',
        'event_date',
        'event_end_date',
        'location',
        'zoom_link',
        'meeting_id',
        'meeting_password',
        'max_participants',
        'current_participants',
        'requires_agreement',
        'agreement_text',
        'is_pro_only',
        'is_featured',
        'materials',
        'specialist',
        'status',
        'is_recurring',
        'recurring_frequency',
        'recurring_day',
        'recurring_time',
        'original_price',
        'referral_wpa_poin',
        'referral_wpa_cash',
        'referral_user_poin',
        'referral_user_cash',
        'referral_distribution_percentage',
        'referral_max_depth',
        'is_license_product',
        'license_duration',
        'license_prefix',
        'ea_file_path',
        'total_sessions',
        'poin_price',
        'fitur_unggulan',
        'layanan_utama',
        'rejection_reason',
        'youtube_tutorials',
        'jenis_modul',
        'is_paid',
        'attendance_type',
        'attendance_id',
        'attendance_code',
        'registration_roles'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

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

    public function getUpcoming($type = null)
    {
        $this->whereIn('status', ['upcoming', 'published', 'aktif'])
            ->where('event_date >', date('Y-m-d H:i:s'))
            ->orderBy('event_date', 'ASC');

        if ($type) {
            $this->where('type', $type);
        }
        return $this->findAll();
    }

    public function getWithWpa($id = null)
    {
        $select = 'layanan_event.*, wpa.name as wpa_name, wpa.photo as wpa_photo, wpa.slug as wpa_slug';
        $builder = $this->select($select)
            ->join('wpa', 'wpa.id = layanan_event.wpa_id', 'left');

        if ($this->db->fieldExists('cwpa_id', 'layanan_event')) {
            $builder->select('cwpa.name as cwpa_name, cwpa.photo as cwpa_photo, cwpa.slug as cwpa_slug')
                ->join('cwpa', 'cwpa.id = layanan_event.cwpa_id', 'left');
        }

        if ($id) {
            return $builder->where('layanan_event.id', $id)->first();
        }
        return $builder->findAll();
    }
}
