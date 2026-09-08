<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Models\WpaModel;
use App\Models\CwpaModel;

class Broadcast extends BaseController
{
    public function index()
    {
        return view('admin/broadcast/index', [
            'title' => 'Broadcast Notifikasi',
            'pageTitle' => 'Kirim Notifikasi',
            'activeMenu' => 'broadcast'
        ]);
    }

    public function getUsersByRole()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $role = $this->request->getGet('role');
        $userModel = new UserModel();
        
        $users = [];
        
        if ($role === 'wpa') {
            $wpaModel = new WpaModel();
            $wpas = $wpaModel->where('status', 'active')->findAll();
            $userIds = array_filter(array_column($wpas, 'user_id'));
            if (!empty($userIds)) {
                $users = $userModel->whereIn('id', $userIds)->select('id, name, email')->findAll();
            }
        } elseif ($role === 'cwpa') {
            $cwpaModel = new CwpaModel();
            $cwpas = $cwpaModel->where('status', 'active')->findAll();
            $userIds = array_filter(array_column($cwpas, 'user_id'));
            if (!empty($userIds)) {
                $users = $userModel->whereIn('id', $userIds)->select('id, name, email')->findAll();
            }
        } elseif ($role === 'pro') {
            $users = $userModel->where('kyc_status', 'approved')->select('id, name, email')->findAll();
        } elseif ($role === 'user') {
            $users = $userModel->select('id, name, email')->findAll();
        }

        return $this->response->setJSON($users);
    }

    public function prepare()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $targetUserId = $this->request->getPost('target_user_id');
        $title = $this->request->getPost('title');
        $message = $this->request->getPost('message');

        if(empty($targetUserId) || empty($title) || empty($message)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Penerima, Judul, dan Pesan wajib diisi']);
        }

        $userModel = new UserModel();
        $downlineIds = [];
        
        if ($targetUserId === 'all') {
            $users = $userModel->select('id')->findAll();
            $downlineIds = array_column($users, 'id');
        } else {
            $downlineIds = $userModel->getNetworkIds($targetUserId, 10);
        }
        
        if(empty($downlineIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Target user tidak ditemukan atau tidak memiliki downline.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'target_ids' => $downlineIds,
            'total' => count($downlineIds)
        ]);
    }

    public function processBatch()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $json = $this->request->getJSON(true);
        $targetIds = $json['target_ids'] ?? [];
        $title = $json['title'] ?? '';
        $message = $json['message'] ?? '';
        $type = $json['type'] ?? 'info';
        $link = $json['link'] ?? '#';

        if(empty($targetIds) || empty($title) || empty($message)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data batch tidak valid.']);
        }

        $notifModel = new NotificationModel();
        
        $count = 0;
        foreach ($targetIds as $uid) {
            $notifModel->createNotification($uid, $title, $message, $type, $link);
            $count++;
        }

        return $this->response->setJSON(['success' => true, 'processed' => $count]);
    }
}
