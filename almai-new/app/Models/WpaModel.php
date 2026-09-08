<?php

namespace App\Models;

use CodeIgniter\Model;

class WpaModel extends Model
{
    protected $table = 'wpa';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'name',
        'slug',
        'photo',
        'specialty',
        'experience',
        'rating',
        'total_classes',
        'bio',
        'instagram',
        'youtube',
        'tiktok',
        'tiktok_secuid',
        'instagram_user_id',
        'instagram_access_token',
        'instagram_token_expires',
        'certifications',
        'status',
        'current_phase',
        'phase_certificates',
        'mql5_widget_url',
        'nik_wpa',
        'nomor_izin_wpa',
        'tanggal_izin_wpa',
        'no_sertifikat_aspebtindo',
        'no_sertifikat_bi',
        'no_sertifikat_bnsp',
        'masa_berlaku',
        'keterangan',
        'jabatan',
        'tanggal_menjabat'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    // Fase 1-8 = tahapan sertifikasi, 9 = Selesai
    public const PHASES = [
        1 => 'Fase 1: Pembekalan & Pendampingan',
        2 => 'Fase 2: Sertifikasi Multilateral',
        3 => 'Fase 3: Sertifikasi Pelatihan LPK',
        4 => 'Fase 4: Sertifikasi LSP PBK',
        5 => 'Fase 5: Sertifikasi TLUP',
        6 => 'Fase 6: Izin WPA',
        7 => 'Fase 7: Persetujuan Derivatif Keuangan',
        8 => 'Fase 8: Persetujuan Derivatif PUVA',
        9 => 'Selesai',
    ];

    public static function getPhaseLabel($id)
    {
        return self::PHASES[(int) $id] ?? 'Unknown Phase';
    }

    public function getFeatured($limit = 3)
    {
        return $this->where('status', 'active')
            ->orderBy('rating', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getFiltered($specialty = null, $search = null)
    {
        $builder = $this->select('wpa.*, users.code_referral as referral_code')->join('users', 'users.id = wpa.user_id')->where('wpa.status', 'active');

        if ($specialty && $specialty !== 'all') {
            $builder->where('wpa.specialty', $specialty);
        }

        if ($search) {
            $builder->groupStart()
                ->like('wpa.name', $search)
                ->orLike('wpa.specialty', $search)
                ->orLike('wpa.bio', $search)
                ->groupEnd();
        }

        return $builder->orderBy('rating', 'DESC')->findAll();
    }

    public function getSpecialties()
    {
        return [
            'Gold Specialist',
            'Forex Specialist',
            'Crypto Specialist',
            'Stock Specialist',
            'Index Specialist',
            'EA Specialist',
            'AI Specialist',
            'Propfirm Specialist',
            'Risk Management',
            'AMANDANA',
            'METAVULUS',
            'REPUBLIC'
        ];
    }

    public function getCertificationsArray($certifications)
    {
        if (is_string($certifications)) {
            return json_decode($certifications, true) ?? [];
        }
        return $certifications ?? [];
    }

    public function findBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    protected function generateSlug(array $data)
    {
        // Only auto-generate slug if not provided or empty
        if (isset($data['data']['name']) && empty($data['data']['slug'])) {
            $slug = $this->createSlug($data['data']['name']);

            // Check if slug exists (for updates, exclude current record)
            $existingId = $data['id'] ?? null;
            $existing = $this->where('slug', $slug);
            if ($existingId) {
                $existing->where('id !=', $existingId);
            }
            $existing = $existing->first();

            // If slug exists, append number
            if ($existing) {
                $counter = 1;
                $originalSlug = $slug;
                while ($this->where('slug', $slug)->where('id !=', $existingId ?? 0)->first()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            $data['data']['slug'] = $slug;
        }
        return $data;
    }

    private function createSlug($name)
    {
        // Convert to lowercase
        $slug = strtolower($name);
        // Remove special characters, keep alphanumeric and spaces
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        // Replace spaces with hyphens
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        // Trim hyphens
        $slug = trim($slug, '-');
        return $slug;
    }
}
