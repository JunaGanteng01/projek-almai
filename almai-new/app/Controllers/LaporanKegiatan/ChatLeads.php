<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\ChatLeadModel;

class ChatLeads extends BaseController
{
    protected $chatLeadModel;

    public function __construct()
    {
        $this->chatLeadModel = new ChatLeadModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Chat Leads - Partnership Admin',
            'leads' => $this->chatLeadModel->orderBy('created_at', 'DESC')->findAll(),
            'activeMenu' => 'chat-leads'
        ];

        return view('laporan-kegiatan/chat_leads/index', $data);
    }
}
