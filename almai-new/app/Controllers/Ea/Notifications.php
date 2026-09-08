<?php

namespace App\Controllers\Ea;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class Notifications extends BaseController
{
    public function index()
    {
        $notificationModel = new NotificationModel();
        $userId = (int) session()->get('userId');
        $alerts = $notificationModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')->paginate(30);

        $data = [
            'title' => 'Pusat Notifikasi & Peringatan',
            'alerts' => $alerts,
            'pager' => $notificationModel->pager,
        ];
        
        return view('ea/notifications', $data);
    }

    public function read(int $id)
    {
        $model = new NotificationModel();
        $userId = (int) session()->get('userId');
        $notification = $model->where('id', $id)->where('user_id', $userId)->first();
        if (!$notification) {
            return redirect()->to('/ceo/notifications')->with('error', 'Notifikasi tidak ditemukan.');
        }
        $model->update($id, ['is_read' => 1]);
        $link = (string) ($notification['link'] ?? '');
        if ($link !== '' && str_starts_with($link, '/')) {
            return redirect()->to($link);
        }
        return redirect()->to('/ceo/notifications');
    }

    public function readAll()
    {
        (new NotificationModel())->markAllAsRead((int) session()->get('userId'));
        return redirect()->to('/ceo/notifications')->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
