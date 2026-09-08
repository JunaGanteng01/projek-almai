<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class Notifications extends BaseController
{
    protected NotificationModel $notifModel;

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

        return view('laporan-kegiatan/notifications', [
            'title' => 'Notifikasi',
            'pageTitle' => 'Notifikasi',
            'pageSubtitle' => 'Semua notifikasi Anda',
            'activeMenu' => 'notifications',
            'notifications' => $notifications
        ]);
    }

    public function read(string $id)
    {
        $notification = $this->notifModel->find($id);
        
        if ($notification && $notification['user_id'] == session()->get('userId')) {
            $this->notifModel->markAsRead($id);
            
            if (!empty($notification['link']) && $notification['link'] !== '#') {
                return redirect()->to($notification['link']);
            }
        }
        
        return redirect()->to('/laporan-kegiatan/notifications');
    }

    public function readAll()
    {
        $userId = session()->get('userId');
        $this->notifModel->markAllAsRead($userId);
        
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
