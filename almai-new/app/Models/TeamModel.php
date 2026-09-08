<?php

namespace App\Models;

use CodeIgniter\Model;

class TeamModel extends Model
{
    protected $table            = 'team';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_number',
        'name',
        'role',
        'photo',
        'is_active',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required|min_length[3]|max_length[255]',
        'role' => 'required|min_length[2]|max_length[100]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
        ],
        'role' => [
            'required' => 'Jabatan harus diisi',
            'min_length' => 'Jabatan minimal 2 karakter',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get all active team members ordered by order_number
     */
    public function getActiveTeam()
    {
        return $this->where('is_active', 1)
            ->orderBy('order_number', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Get all team members with pagination and search
     */
    public function getTeamWithPagination($search = null, $perPage = 20)
    {
        $builder = $this->orderBy('order_number', 'ASC')->orderBy('id', 'ASC');

        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('role', $search)
                ->groupEnd();
        }

        return $builder->paginate($perPage);
    }
}
