<?php

namespace App\Controllers;

use App\Models\ToolsModel;

class Tools extends BaseController
{
    protected $toolsModel;

    public function __construct()
    {
        $this->toolsModel = new ToolsModel();
    }

    public function index()
    {
        $tools = $this->toolsModel->getActive();
        
        // Decode JSON fields
        foreach ($tools as &$tool) {
            $tool['features'] = json_decode($tool['features'] ?? '[]', true) ?: [];
            $tool['compatibility'] = json_decode($tool['compatibility'] ?? '[]', true) ?: [];
        }

        return view('pages/tools/index', [
            'title' => 'Trading Tools - ALMAI',
            'tools' => $tools,
            'categories' => $this->toolsModel->getCategories()
        ]);
    }

    public function detail($id)
    {
        $tool = $this->toolsModel->where('status', 'active')->find($id);

        if (!$tool) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Decode JSON fields
        $tool['features'] = json_decode($tool['features'] ?? '[]', true) ?: [];
        $tool['compatibility'] = json_decode($tool['compatibility'] ?? '[]', true) ?: [];

        return view('pages/tools/detail', [
            'title' => $tool['name'] . ' - ALMAI Tools',
            'tool' => $tool
        ]);
    }
}
