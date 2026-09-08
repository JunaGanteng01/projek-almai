<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\TeamModel;

class Team extends BaseController
{
    protected $teamModel;

    public function __construct()
    {
        $this->teamModel = new TeamModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');

        $data = [
            'title' => 'Manajemen Tim - Partnership Admin',
            'team' => $this->teamModel->getTeamWithPagination($search, 20),
            'pager' => $this->teamModel->pager,
            'search' => $search,
            'activeMenu' => 'tim'
        ];

        return view('laporan-kegiatan/team/index', $data);
    }
}
