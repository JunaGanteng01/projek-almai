<?php

namespace App\Models;

use CodeIgniter\Model;

class CwpaFollowerModel extends Model
{
    protected $table            = 'cwpa_followers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'cwpa_id', 'created_at'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Check if user is following CWPA
     */
    public function isFollowing($userId, $cwpaId)
    {
        return $this->where('user_id', $userId)
            ->where('cwpa_id', $cwpaId)
            ->countAllResults() > 0;
    }

    /**
     * Toggle follow status
     * Returns true if following, false if unfollowed
     */
    public function toggleFollow($userId, $cwpaId)
    {
        $existing = $this->where('user_id', $userId)
            ->where('cwpa_id', $cwpaId)
            ->first();

        if ($existing) {
            $this->delete($existing['id']);
            return false;
        } else {
            $this->insert([
                'user_id' => $userId,
                'cwpa_id' => $cwpaId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return true;
        }
    }

    /**
     * Get follower count for a CWPA
     */
    public function getFollowerCount($cwpaId)
    {
        return $this->where('cwpa_id', $cwpaId)->countAllResults();
    }
}
