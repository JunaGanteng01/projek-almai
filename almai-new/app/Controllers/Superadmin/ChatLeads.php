<?php

namespace App\Controllers\Superadmin;

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
            'title' => 'Chat Leads',
            'leads' => $this->chatLeadModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('superadmin/chat_leads/index', $data);
    }

    public function delete($id)
    {
        if ($this->chatLeadModel->delete($id)) {
            return redirect()->to('/superadmin/chat-leads')->with('success', 'Lead berhasil dihapus');
        }
        return redirect()->to('/superadmin/chat-leads')->with('error', 'Gagal menghapus lead');
    }
}
