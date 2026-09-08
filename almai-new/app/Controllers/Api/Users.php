<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class Users extends ResourceController
{
    protected $format = 'json';

    /**
     * Get user statistics (total count)
     * GET /api/users/stats
     */
    public function stats()
    {
        try {
            $userModel = new \App\Models\UserModel();
            $total = $userModel->countAllResults();
            
            $latest = $userModel->select('name, avatar, created_at')
                                ->orderBy('created_at', 'DESC')
                                ->first();
            
            return $this->respond([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'latest' => [
                        'name' => $latest['name'] ?? 'User',
                        'avatar' => !empty($latest['avatar']) ? base_url('file/'.$latest['avatar']) : base_url('images/default-avatar.png'),
                        'created_at' => $latest['created_at'] ?? date('Y-m-d H:i:s')
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'success' => false,
                'message' => 'Failed to fetch user stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent users
     * GET /api/users/recent?limit=20
     */
    public function recent()
    {
        try {
            $limit = $this->request->getGet('limit') ?? 20;
            $limit = min(max((int)$limit, 1), 100); // Between 1-100
            
            $userModel = new \App\Models\UserModel();
            $recentUsers = $userModel->select('name, avatar, created_at')
                                     ->orderBy('created_at', 'DESC')
                                     ->limit($limit)
                                     ->find();
            
            // Format the response
            $users = array_map(function($user) {
                return [
                    'name' => $user['name'],
                    'avatar' => !empty($user['avatar']) ? base_url('file/'.$user['avatar']) : base_url('images/default-avatar.png'),
                    'created_at' => $user['created_at']
                ];
            }, $recentUsers);
            
            return $this->respond([
                'success' => true,
                'data' => [
                    'users' => $users,
                    'count' => count($users)
                ]
            ]);
        } catch (\Exception $e) {
            return $this->fail([
                'success' => false,
                'message' => 'Failed to fetch recent users',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
