<?php

namespace App\Models;

use CodeIgniter\Model;

class FollowerModel extends Model
{
    protected $table            = 'followers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'wpa_id', 'cwpa_id', 'created_at'];

    // Dates
    protected $useTimestamps = false; // Manually managing created_at given existing structure
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
     * Check if user is following WPA or CWPA
     */
    public function isFollowing($userId, $wpaId = null, $cwpaId = null)
    {
        $builder = $this->where('user_id', $userId);

        if ($wpaId) {
            $builder->where('wpa_id', $wpaId);
        } elseif ($cwpaId) {
            $builder->where('cwpa_id', $cwpaId);
        } else {
            return false;
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Toggle follow status
     * Returns true if following, false if unfollowed
     */
    public function toggleFollow($userId, $wpaId = null, $cwpaId = null)
    {
        $builder = $this->where('user_id', $userId);

        if ($wpaId) {
            $builder->where('wpa_id', $wpaId);
        } elseif ($cwpaId) {
            $builder->where('cwpa_id', $cwpaId);
        } else {
            return false;
        }

        $existing = $builder->first();

        if ($existing) {
            $this->delete($existing['id']);
            return false;
        } else {
            $this->insert([
                'user_id' => $userId,
                'wpa_id'  => $wpaId,
                'cwpa_id' => $cwpaId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return true;
        }
    }

    /**
     * Get follower count
     */
    public function getFollowerCount($wpaId = null, $cwpaId = null)
    {
        if ($wpaId) {
            return $this->where('wpa_id', $wpaId)->countAllResults();
        } elseif ($cwpaId) {
            return $this->where('cwpa_id', $cwpaId)->countAllResults();
        }
        return 0;
    }
}
