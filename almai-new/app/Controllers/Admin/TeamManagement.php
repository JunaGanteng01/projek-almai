<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TeamModel;

class TeamManagement extends BaseController
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
            'title' => 'Manajemen Tim',
            'team' => $this->teamModel->getTeamWithPagination($search, 20),
            'pager' => $this->teamModel->pager,
            'search' => $search,
        ];

        return view('admin/team/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Anggota Tim',
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/team/create', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'role' => 'required|min_length[2]|max_length[100]',
            'order_number' => 'required|integer',
            'photo' => 'uploaded[photo]|max_size[photo,2048]|is_image[photo]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        $photoName = null;

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = 'team_' . time() . '_' . $photo->getRandomName();
            $photo->move(WRITEPATH . 'uploads/team', $photoName);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'order_number' => $this->request->getPost('order_number'),
            'photo' => $photoName ? 'uploads/team/' . $photoName : null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->teamModel->insert($data)) {
            return redirect()->to('/admin/tim')->with('success', 'Anggota tim berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan anggota tim');
    }

    public function edit($id)
    {
        $team = $this->teamModel->find($id);

        if (!$team) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Anggota Tim',
            'team' => $team,
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/team/edit', $data);
    }

    public function update($id)
    {
        $team = $this->teamModel->find($id);

        if (!$team) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'role' => 'required|min_length[2]|max_length[100]',
            'order_number' => 'required|integer',
        ];

        // Only validate photo if uploaded
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid()) {
            $rules['photo'] = 'max_size[photo,2048]|is_image[photo]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle photo upload
        $photoName = $team['photo'];

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Delete old photo
            if ($team['photo'] && file_exists(WRITEPATH . $team['photo'])) {
                @unlink(WRITEPATH . $team['photo']);
            }

            $photoName = 'team_' . time() . '_' . $photo->getRandomName();
            $photo->move(WRITEPATH . 'uploads/team', $photoName);
            $photoName = 'uploads/team/' . $photoName;
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'order_number' => $this->request->getPost('order_number'),
            'photo' => $photoName,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->teamModel->update($id, $data)) {
            return redirect()->to('/admin/tim')->with('success', 'Anggota tim berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate anggota tim');
    }

    public function delete($id)
    {
        $team = $this->teamModel->find($id);

        if (!$team) {
            return redirect()->to('/admin/tim')->with('error', 'Anggota tim tidak ditemukan');
        }

        // Delete photo
        if ($team['photo'] && file_exists(WRITEPATH . $team['photo'])) {
            @unlink(WRITEPATH . $team['photo']);
        }

        if ($this->teamModel->delete($id)) {
            return redirect()->to('/admin/tim')->with('success', 'Anggota tim berhasil dihapus');
        }

        return redirect()->to('/admin/tim')->with('error', 'Gagal menghapus anggota tim');
    }
}
