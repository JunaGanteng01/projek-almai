<?php

namespace App\Models;

use CodeIgniter\Model;

class KycSubmissionModel extends Model
{
    protected $table = 'kyc_submissions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'full_name', 'nik', 'birth_place', 'birth_date', 'gender',
        'phone', 'address', 'province', 'city', 'postal_code', 'ktp_photo',
        'bank_name', 'bank_branch', 'account_number', 'account_name',
        'experience', 'investment_goal', 'risk_profile'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getByUserId($userId)
    {
        return $this->where('user_id', $userId)->first();
    }
}
