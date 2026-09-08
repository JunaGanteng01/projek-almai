<?php

namespace App\Controllers\Admin;

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

        return view('admin/chat_leads/index', $data);
    }

    public function delete($id)
    {
        if ($this->chatLeadModel->delete($id)) {
            return redirect()->to('/admin/chat-leads')->with('success', 'Lead berhasil dihapus');
        }
        return redirect()->to('/admin/chat-leads')->with('error', 'Gagal menghapus lead');
    }
}
