<?php

namespace App\Models;

use CodeIgniter\Model;

class CwpaSubmissionModel extends Model
{
    protected $table = 'cwpa_submissions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'ktp_number',
        'npwp_number',
        'address',
        'cv',
        'ijazah',
        'sertifikat_multilateral',
        'sertifikat_lsp_pbk',
        'tanda_lulus_bappebti',
        'surat_rekomendasi',
        'ktp',
        'npwp',
        'bukti_pelaporan_spt',
        'skck',
        'photo',
        'surat_pernyataan_cwpa',
        'permohonan_izin_wapebti',
        'sk_tidak_pidana',
        'surat_pernyataan_cwpa_kerja',
        'status',
        'whatsapp',
        'social_media',
        'trading_experience',
        'specialties',
        'has_ijazah_s1',
        'has_skck_card',
        'willing_to_make_skck',
        'has_clean_record',
        'has_felony_record',
        'willing_to_make_felony_statement',
        'has_bankruptcy_record',
        'payment_status',
        'transfer_proof',
        'admin_note',
        'verified_at',
        'verified_by'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getByUserId($userId)
    {
        return $this->where('user_id', $userId)->first();
    }
}
