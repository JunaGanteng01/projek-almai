<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class Notifications extends BaseController
{
    protected $notifModel;

    public function __construct()
    {
        $this->notifModel = new NotificationModel();
    }

    public function index()
    {
        $userId = session()->get('userId');
        $notifications = $this->notifModel->getByUserId($userId, 50);

        return view('user/notifications', [
            'title' => 'Notifikasi',
            'pageTitle' => 'Notifikasi',
            'pageSubtitle' => 'Semua notifikasi Anda',
            'activeMenu' => 'notifications',
            'notifications' => $notifications
        ]);
    }

    public function read($id)
    {
        $userId = session()->get('userId');
        $notif = $this->notifModel->find($id);

        if ($notif && $notif['user_id'] == $userId) {
            $this->notifModel->markAsRead($id);
            if (!empty($notif['link']) && $notif['link'] !== '#') {
                return redirect()->to($notif['link']);
            }
        }

        return redirect()->to('/user/notifications');
    }

    public function readAll()
    {
        $userId = session()->get('userId');
        $this->notifModel->markAllAsRead($userId);
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }
}
