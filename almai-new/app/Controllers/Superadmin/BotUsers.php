<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use CodeIgniter\Database\ConnectionInterface;

class BotUsers extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $search = $this->request->getGet('search');
        $mode = $this->request->getGet('mode');
        $status = $this->request->getGet('status');

        // Fetch bots with user details and exchange details
        $builder = $db->table('bots');
        $builder->select('bots.id, bots.user_id, bots.name as bot_name, bots.symbol, bots.strategy, bots.status, bots.config, bots.total_profit, users.name as user_name, user_exchange_keys.account_name, user_exchange_keys.exchange');
        $builder->join('users', 'users.id = bots.user_id', 'left');
        $builder->join('user_exchange_keys', 'user_exchange_keys.id = bots.user_exchange_key_id', 'left');
        
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('users.name', $search)
                    ->orLike('bots.name', $search)
                    ->orLike('bots.symbol', $search)
                    ->groupEnd();
        }
        if (!empty($status) && $status !== 'all') {
            $builder->where('bots.status', $status);
        }

        $builder->orderBy('users.name', 'ASC');
        
        $botUsers = $builder->get()->getResult();

        $stats = [
            'total_users' => 0,
            'total_active' => 0,
            'total_inactive' => 0,
            'total_live' => 0,
            'total_demo' => 0,
        ];
        
        $uniqueUsers = [];
        $groupedUsers = [];
        foreach ($botUsers as $row) {
            $config = json_decode($row->config ?? '{}', true);
            $isLive = !empty($config['liveMode']);
            
            // Filter by mode (since it's in JSON)
            if ($mode === 'live' && !$isLive) continue;
            if ($mode === 'demo' && $isLive) continue;

            // Compute stats
            if ($row->status === 'active') {
                $stats['total_active']++;
            } else {
                $stats['total_inactive']++;
            }
            
            if ($isLive) {
                $stats['total_live']++;
            } else {
                $stats['total_demo']++;
            }
            $uniqueUsers[$row->user_id] = true;

            $userId = $row->user_id;
            if (!isset($groupedUsers[$userId])) {
                $groupedUsers[$userId] = [
                    'user_name' => $row->user_name,
                    'total_profit_all' => 0,
                    'bots' => []
                ];
            }
            $groupedUsers[$userId]['bots'][] = $row;
            $groupedUsers[$userId]['total_profit_all'] += $row->total_profit;
        }

        $stats['total_users'] = count($uniqueUsers);

        $data = [
            'title' => 'Bot Users',
            'groupedUsers' => array_values($groupedUsers),
            'stats' => $stats,
            'filterSearch' => $search,
            'filterMode' => $mode,
            'filterStatus' => $status
        ];

        return view('superadmin/bot_users/index', $data);
    }

    public function exportPdf()
    {
        $db = \Config\Database::connect();
        
        $search = $this->request->getGet('search');
        $mode = $this->request->getGet('mode');
        $status = $this->request->getGet('status');

        $builder = $db->table('bots');
        $builder->select('bots.id, bots.user_id, bots.name as bot_name, bots.symbol, bots.strategy, bots.status, bots.config, bots.total_profit, users.name as user_name, user_exchange_keys.account_name, user_exchange_keys.exchange');
        $builder->join('users', 'users.id = bots.user_id', 'left');
        $builder->join('user_exchange_keys', 'user_exchange_keys.id = bots.user_exchange_key_id', 'left');
        
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('users.name', $search)
                    ->orLike('bots.name', $search)
                    ->orLike('bots.symbol', $search)
                    ->groupEnd();
        }
        if (!empty($status) && $status !== 'all') {
            $builder->where('bots.status', $status);
        }

        $builder->orderBy('users.name', 'ASC');
        
        $botUsers = $builder->get()->getResult();

        $groupedUsers = [];
        foreach ($botUsers as $row) {
            $config = json_decode($row->config ?? '{}', true);
            $isLive = !empty($config['liveMode']);
            
            if ($mode === 'live' && !$isLive) continue;
            if ($mode === 'demo' && $isLive) continue;

            $userId = $row->user_id;
            if (!isset($groupedUsers[$userId])) {
                $groupedUsers[$userId] = [
                    'user_name' => $row->user_name,
                    'total_profit_all' => 0,
                    'bots' => []
                ];
            }
            $groupedUsers[$userId]['bots'][] = $row;
            $groupedUsers[$userId]['total_profit_all'] += $row->total_profit;
        }

        $data = [
            'groupedUsers' => array_values($groupedUsers)
        ];

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);
        
        $html = view('superadmin/bot_users/pdf_export', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $filename = 'Laporan_Bot_Users_' . date('Ymd_His') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }
}
