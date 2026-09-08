<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiPesertaModel extends Model
{
    protected $table = 'absensi_peserta';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'kegiatan_type',
        'kegiatan_id',
        'poin_awarded',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function isAlreadyCheckedIn($userId, $kegiatanType, $kegiatanId)
    {
        return $this->where([
            'user_id' => $userId,
            'kegiatan_type' => $kegiatanType,
            'kegiatan_id' => $kegiatanId
        ])->first() !== null;
    }

    public function getAbsensiByUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getWithUser()
    {
        return $this->select('absensi_peserta.*, users.name, users.email, users.phone as no_hp')
                    ->join('users', 'users.id = absensi_peserta.user_id', 'left');
    }
}
