<?php

namespace App\Controllers;

use App\Models\PoinModel;
use CodeIgniter\Controller;

class Feedback extends BaseController
{
    public function index()
    {
        $userId = session()->get('userId') ?? session()->get('cwpa_user_id');
        $isLoggedIn = !empty($userId);

        $hasSubmitted = false;
        if ($isLoggedIn) {
            $db = \Config\Database::connect();
            $existing = $db->table('feedbacks')->where('user_id', $userId)->countAllResults();
            $hasSubmitted = $existing > 0;
        }

        return view('pages/feedback', [
            'title' => 'Feedback - ALMAI',
            'isLoggedIn' => $isLoggedIn,
            'hasSubmitted' => $hasSubmitted
        ]);
    }

    public function submit()
    {
        $userId = session()->get('userId') ?? session()->get('cwpa_user_id');
        
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk memberikan feedback.');
        }

        $db = \Config\Database::connect();
        $existing = $db->table('feedbacks')->where('user_id', $userId)->countAllResults();
        
        if ($existing > 0) {
            return redirect()->back()->with('error', 'Anda sudah pernah mengirimkan feedback. Terima kasih atas partisipasi Anda!');
        }

        $rating = $this->request->getPost('rating');
        $message = $this->request->getPost('message');

        if (!$rating || !$message) {
            return redirect()->back()->with('error', 'Harap isi rating dan pesan feedback Anda.');
        }

        // Insert feedback
        $db->table('feedbacks')->insert([
            'user_id' => $userId,
            'rating' => $rating,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $feedbackId = $db->insertID();

        // Award points
        $poinModel = new PoinModel();
        $poinModel->insert([
            'user_id' => $userId,
            'point' => 300,
            'type' => 'reward',
            'description' => 'Reward mengisi form feedback ALMAI',
            'pointable_type' => 'feedback',
            'pointable_id' => $feedbackId
        ]);

        // Auto redirect to dashboard based on role
        $role = session()->get('userRole');
        $dashboardPath = '/user/dashboard'; // Default fallback
        if ($role) {
            $dashboardPath = \App\Models\LevelModel::getDashboardPath(\App\Models\LevelModel::levelFromRoleString($role));
        }

        return redirect()->to($dashboardPath)->with('success', 'Terima kasih atas feedback Anda! Anda mendapatkan 300 ALMAI Poin.');
    }
}
