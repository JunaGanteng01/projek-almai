<?php

namespace App\Controllers\Superadmin;

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
        $notifications = $this->notifModel->where('user_id', $userId)
                                          ->orderBy('created_at', 'DESC')
                                          ->findAll();

        return view('superadmin/notifications', [
            'title' => 'Notifikasi',
            'pageTitle' => 'Notifikasi',
            'pageSubtitle' => 'Semua notifikasi Anda',
            'activeMenu' => 'notifications',
            'notifications' => $notifications
        ]);
    }

    public function read($id)
    {
        $notification = $this->notifModel->find($id);
        
        if ($notification && $notification['user_id'] == session()->get('userId')) {
            $this->notifModel->markAsRead($id);
            
            if (!empty($notification['link']) && $notification['link'] !== '#') {
                return redirect()->to($notification['link']);
            }
        }
        
        return redirect()->to('/superadmin/notifications');
    }

    public function readAll()
    {
        $userId = session()->get('userId');
        $this->notifModel->markAllAsRead($userId);
        
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
