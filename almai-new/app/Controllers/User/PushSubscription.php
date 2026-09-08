<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserPushSubscriptionModel;

class PushSubscription extends BaseController
{
    public function subscribe()
    {
        $userId = session()->get('userId');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $json = $this->request->getJSON();
        
        if (!isset($json->endpoint) || !isset($json->keys->p256dh) || !isset($json->keys->auth)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid subscription data'])->setStatusCode(400);
        }

        $subModel = new UserPushSubscriptionModel();
        
        // Cek apakah endpoint sudah ada
        $existing = $subModel->where('endpoint', $json->endpoint)->first();

        $data = [
            'user_id'  => $userId,
            'endpoint' => $json->endpoint,
            'p256dh'   => $json->keys->p256dh,
            'auth'     => $json->keys->auth,
        ];

        if ($existing) {
            $subModel->update($existing['id'], $data);
        } else {
            $subModel->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Subscription saved']);
    }
}
