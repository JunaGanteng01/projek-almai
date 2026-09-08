<?php

namespace App\Controllers\Cwpa;

use App\Controllers\BaseController;
use App\Models\ChatLeadModel;
use App\Models\UserModel;
use App\Models\CwpaModel;
use App\Models\NotificationModel;
use App\Models\TransaksiModel;
use App\Models\LayananModel;

class ChatLeads extends BaseController
{
    public function index()
    {
        $cwpaId = $this->getCwpaId();
        if (!$cwpaId) return redirect()->to('/login')->with('error', 'Profil CWPA tidak ditemukan');

        $userId = session()->get('userId');
        $userModel = new UserModel();
        $chatLeadModel = new ChatLeadModel();
        
        $cwpaUser = $userModel->find($userId);
        $referralCode = $cwpaUser['code_referral'] ?? null;

        // 1. Get Anonymous Chat Leads (from chat_leads table)
        $anonymousLeads = [];
        if ($referralCode) {
            $anonymousLeads = $chatLeadModel->where('referral_code', $referralCode)
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }

        // 2. Get Registered Users (Referrals & Buyers)
        $allDownlineIds = $this->getDownlineIds($cwpaUser);
        $buyerUserIds = $this->getBuyerIds($cwpaId);
        
        $ownedUserIds = array_unique(array_merge($allDownlineIds, $buyerUserIds));
        
        $registeredUsers = [];
        if (!empty($ownedUserIds)) {
            $registeredUsers = $userModel->whereIn('id', $ownedUserIds)
                ->orderBy('created_at', 'DESC')
                ->findAll();
            
            // Fetch all chat leads for these users to enrich data
            $phones = array_filter(array_column($registeredUsers, 'phone'));
            $leadsMap = [];
            if (!empty($phones)) {
                $leadsData = $chatLeadModel->whereIn('whatsapp', $phones)->findAll();
                foreach ($leadsData as $ld) {
                    $leadsMap[$ld->whatsapp] = $ld;
                }
            }

            foreach ($registeredUsers as &$u) {
                $u['is_buyer'] = in_array($u['id'], $buyerUserIds);
                $u['is_referral'] = in_array($u['id'], $allDownlineIds);
                $u['lead_info'] = $leadsMap[$u['phone']] ?? null;
            }
        }

        return view('cwpa/chat_leads/index', [
            'title' => 'Chat Lead - CWPA Dashboard',
            'activeMenu' => 'chat-leads',
            'anonymousLeads' => $anonymousLeads,
            'registeredUsers' => $registeredUsers,
            'referralCode' => $referralCode
        ]);
    }

    public function sendNotification()
    {
        $userIds = $this->request->getPost('user_ids');
        $title = $this->request->getPost('title');
        $message = $this->request->getPost('message');

        if (empty($userIds) || empty($title) || empty($message)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        $notifModel = new NotificationModel();
        $count = 0;

        foreach ($userIds as $id) {
            $notifModel->insert([
                'user_id' => $id,
                'title' => $title,
                'message' => $message,
                'type' => 'info',
                'is_read' => 0
            ]);
            $count++;
        }

        return $this->response->setJSON(['status' => 'success', 'message' => "$count Notifikasi berhasil terkirim"]);
    }

    private function getCwpaId()
    {
        $cwpaId = session()->get('cwpaId');
        if (!$cwpaId) {
            $userId = session()->get('userId');
            $cwpaModel = new CwpaModel();
            $cwpa = $cwpaModel->where('user_id', $userId)->first();
            if ($cwpa) {
                $cwpaId = $cwpa['id'];
                session()->set('cwpaId', $cwpaId);
            }
        }
        return $cwpaId;
    }

    private function getDownlineIds($cwpaUser)
    {
        $userModel = new UserModel();
        $allDownlineIds = [];
        $currentLevelUsers = [$cwpaUser];
        $processedIds = [$cwpaUser['id']];

        // CWPA might want to see deeper or different downline, re-using standard 10 levels
        for ($level = 1; $level <= 10; $level++) {
            if (empty($currentLevelUsers)) break;

            $parentIds = [];
            $parentCodes = [];

            foreach ($currentLevelUsers as $p) {
                $parentIds[] = $p['id'];
                if (!empty($p['code_referral'])) $parentCodes[] = $p['code_referral'];
                if (!empty($p['referral_code'])) $parentCodes[] = $p['referral_code'];
            }

            $parentIds = array_unique(array_filter($parentIds));
            $parentCodes = array_unique(array_filter($parentCodes));

            if (empty($parentIds) && empty($parentCodes)) break;

            $nextLevelUsersBuilder = $userModel;
            if (count($processedIds) < 1000) {
                $nextLevelUsersBuilder->whereNotIn('id', $processedIds);
            }
            
            $nextLevelUsersBuilder->groupStart();
            if (!empty($parentIds)) {
                $nextLevelUsersBuilder->whereIn('referred_by', $parentIds);
            }
            if (!empty($parentCodes)) {
                $nextLevelUsersBuilder->orWhereIn('affiliator_code', $parentCodes);
            }
            $nextLevelUsersBuilder->groupEnd();

            $newDownlines = $nextLevelUsersBuilder->findAll();
            
            if (count($processedIds) >= 1000) {
                $newDownlines = array_filter($newDownlines, fn($u) => !in_array($u['id'], $processedIds));
            }

            if (empty($newDownlines)) break;

            $currentLevelUsers = [];
            foreach ($newDownlines as $d) {
                $allDownlineIds[] = $d['id'];
                $processedIds[] = $d['id'];
                $currentLevelUsers[] = $d;
            }
        }

        return array_unique($allDownlineIds);
    }

    private function getBuyerIds($cwpaId)
    {
        $layananModel = new LayananModel();
        $allLayanan = $layananModel->getByCwpaId($cwpaId);
        
        if (empty($allLayanan)) return [];

        $layananIds = array_filter(array_column($allLayanan, 'id'));
        $layananNames = array_unique(array_filter(array_map(function ($item) {
            return $item['name'] ?? $item['title'] ?? null;
        }, $allLayanan)));

        $tModel = new TransaksiModel();
        $tModel->select('DISTINCT(user_id) as user_id');
        $tModel->where('status', 'confirmed');
        
        $tModel->groupStart();
        if (!empty($layananIds)) {
            $tModel->whereIn('layanan_id', $layananIds);
        }
        if (!empty($layananNames)) {
            $tModel->orWhereIn('product_name', $layananNames);
            if (count($layananNames) < 50) {
                foreach ($layananNames as $name) {
                    $tModel->orLike('product_name', $name . ' - ', 'after');
                }
            }
        }
        $tModel->groupEnd();

        $result = $tModel->findAll();
        return array_column($result, 'user_id');
    }
}
