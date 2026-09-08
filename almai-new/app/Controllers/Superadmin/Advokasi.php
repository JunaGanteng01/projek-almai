<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\LayananPengaduanModel;

class Advokasi extends BaseController
{
    /**
     * Display a list of complaints
     */
    public function index()
    {
        $model = new LayananPengaduanModel();
        
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $category = $this->request->getGet('category');
        $subcategory = $this->request->getGet('subcategory');
        
        $builder = $model->select('layanan_pengaduan.*, users.name as user_name, users.email as user_email')
                         ->join('users', 'users.id = layanan_pengaduan.user_id', 'left');
        
        if ($status) {
            $builder->where('layanan_pengaduan.status', $status);
        }

        if ($category) {
            $builder->where('layanan_pengaduan.category_problem', $category);
        }

        if ($subcategory) {
            $builder->where('layanan_pengaduan.sub_category', $subcategory);
        }
        
        if ($search) {
            $builder->groupStart()
                    ->like('layanan_pengaduan.name', $search)
                    ->orLike('layanan_pengaduan.email', $search)
                    ->orLike('layanan_pengaduan.broker_name', $search)
                    ->orLike('layanan_pengaduan.ktp_number', $search)
                    ->groupEnd();
        }
        
        $complaints = $builder->orderBy('layanan_pengaduan.created_at', 'DESC')
                              ->paginate(15, 'default');
        
        // Get unique categories and subcategories for filters
        $categories = $model->distinct()->select('category_problem')->orderBy('category_problem', 'ASC')->findAll();
        $subcategories = $model->distinct()->select('sub_category')->where('sub_category !=', '')->orderBy('sub_category', 'ASC')->findAll();

        return view('superadmin/layanan/pengaduan_index', [
            'title' => 'Kelola Pengaduan Advokasi',
            'activeMenu' => 'advokasi',
            'complaints' => $complaints,
            'pager' => $model->pager,
            'currentSearch' => $search,
            'currentStatus' => $status,
            'currentCategory' => $category,
            'currentSubcategory' => $subcategory,
            'categories' => array_column($categories, 'category_problem'),
            'subcategories' => array_column($subcategories, 'sub_category'),
            'canWrite' => $this->canWriteAdmin()
        ]);
    }

    /**
     * View detailed complaint
     */
    public function show($id)
    {
        $model = new LayananPengaduanModel();
        $complaint = $model->select('layanan_pengaduan.*, users.name as user_name, users.email as user_email')
                           ->join('users', 'users.id = layanan_pengaduan.user_id', 'left')
                           ->find($id);
        
        if (!$complaint) {
            return redirect()->to('/superadmin/advokasi')->with('error', 'Data pengaduan tidak ditemukan.');
        }

        return view('superadmin/layanan/pengaduan_detail', [
            'title' => 'Detail Pengaduan - ' . $complaint['name'],
            'activeMenu' => 'advokasi',
            'complaint' => $complaint,
            'canWrite' => $this->canWriteAdmin()
        ]);
    }

    /**
     * Update status of complaint
     */
    public function updateStatus($id)
    {
        if (!$this->canWriteAdmin()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $status = $this->request->getPost('status');
        $validStatuses = ['pending_payment', 'review', 'investigating', 'legal_process', 'resolved', 'rejected'];

        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Status tidak valid.']);
        }

        $model = new LayananPengaduanModel();
        if ($model->update($id, ['status' => $status])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Status berhasil diperbarui.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui status.']);
    }

    /**
     * Delete complaint
     */
    public function delete($id)
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $model = new LayananPengaduanModel();
        $complaint = $model->find($id);

        if ($complaint) {
            // Optional: delete attachment file
            if ($complaint['file_attachment'] && file_exists(FCPATH . 'uploads/pengaduan/' . $complaint['file_attachment'])) {
                unlink(FCPATH . 'uploads/pengaduan/' . $complaint['file_attachment']);
            }
            $model->delete($id);
            return redirect()->to('/superadmin/advokasi')->with('success', 'Data pengaduan berhasil dihapus.');
        }

        return redirect()->to('/superadmin/advokasi')->with('error', 'Data gagal dihapus.');
    }

    /**
     * Display Advocacy Settings
     */
    public function setting()
    {
        $settingModel = new \App\Models\AdvokasiSettingModel();
        $settings = $settingModel->getSettings();

        return view('superadmin/advokasi/setting', [
            'title' => 'Pengaturan Program Advokasi',
            'activeMenu' => 'advokasi',
            'settings' => $settings,
            'canWrite' => $this->canWriteAdmin()
        ]);
    }

    /**
     * Save Advocacy Settings
     */
    public function saveSetting()
    {
        if (!$this->canWriteAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $settingModel = new \App\Models\AdvokasiSettingModel();
        
        $data = [
            'price' => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
            'ea_duration' => $this->request->getPost('ea_duration') ?: 30,
            'zoom_link' => $this->request->getPost('zoom_link'),
            'zoom_meeting_id' => $this->request->getPost('zoom_meeting_id'),
            'zoom_password' => $this->request->getPost('zoom_password'),
            'canva_embed_url' => json_encode($this->request->getPost('canva_links') ?: []),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Create directory if not exists
        if (!is_dir(FCPATH . 'uploads/advokasi')) {
            mkdir(FCPATH . 'uploads/advokasi', 0777, true);
        }

        // Handle Materials (JSON + Files)
        $materialTitles = $this->request->getPost('material_titles') ?: [];
        $materialSubtitles = $this->request->getPost('material_subtitles') ?: [];
        $materialIcons = $this->request->getPost('material_icons') ?: [];
        $existingMaterialFiles = $this->request->getPost('existing_material_files') ?: [];
        $materialFiles = $this->request->getFiles();

        $materials = [];
        for ($i = 0; $i < count($materialTitles); $i++) {
            if (!empty($materialTitles[$i])) {
                $filePath = $existingMaterialFiles[$i] ?? '#';
                
                // Check if a new file was uploaded for this index
                if (isset($materialFiles['material_upload_files'][$i])) {
                    $mFile = $materialFiles['material_upload_files'][$i];
                    if ($mFile->isValid() && !$mFile->hasMoved()) {
                        $mNewName = $mFile->getClientName();
                        $mFile->move(FCPATH . 'uploads/advokasi', $mNewName, true);
                        $filePath = 'uploads/advokasi/' . $mNewName;
                    }
                }

                $materials[] = [
                    'title' => $materialTitles[$i],
                    'subtitle' => $materialSubtitles[$i] ?? '',
                    'icon' => $materialIcons[$i] ?? 'fa-file-pdf',
                    'file' => $filePath
                ];
            }
        }
        $data['materials'] = json_encode($materials);

        // Handle Software (JSON + Files)
        $softwareTitles = $this->request->getPost('software_titles') ?: [];
        $existingSoftwareFiles = $this->request->getPost('existing_software_files') ?: [];
        
        $software = [];
        for ($i = 0; $i < count($softwareTitles); $i++) {
            if (!empty($softwareTitles[$i])) {
                $filePath = $existingSoftwareFiles[$i] ?? '#';
                
                // Check if a new file was uploaded for this index
                if (isset($materialFiles['software_upload_files'][$i])) {
                    $sFile = $materialFiles['software_upload_files'][$i];
                    if ($sFile->isValid() && !$sFile->hasMoved()) {
                        $sNewName = $sFile->getClientName();
                        $sFile->move(FCPATH . 'uploads/advokasi', $sNewName, true);
                        $filePath = 'uploads/advokasi/' . $sNewName;
                    }
                }

                $software[] = [
                    'title' => $softwareTitles[$i],
                    'file' => $filePath
                ];
            }
        }
        $data['software'] = json_encode($software);

        // Handle Multiple EA (JSON + Files)
        $eaTitles = $this->request->getPost('ea_titles') ?: [];
        $existingEaFiles = $this->request->getPost('existing_ea_files') ?: [];
        
        $eaList = [];
        for ($i = 0; $i < count($eaTitles); $i++) {
            if (!empty($eaTitles[$i])) {
                $filePath = $existingEaFiles[$i] ?? '#';
                
                // Check if a new file was uploaded for this index
                if (isset($materialFiles['ea_upload_files'][$i])) {
                    $eFile = $materialFiles['ea_upload_files'][$i];
                    if ($eFile->isValid() && !$eFile->hasMoved()) {
                        $eNewName = $eFile->getClientName();
                        $eFile->move(FCPATH . 'uploads/advokasi', $eNewName, true);
                        $filePath = 'uploads/advokasi/' . $eNewName;
                    }
                }

                $eaList[] = [
                    'title' => $eaTitles[$i],
                    'file' => $filePath
                ];
            }
        }
        $data['ea_list'] = json_encode($eaList);

        // Handle Thumbnail Upload
        $thumbnailFile = $this->request->getFile('thumbnail');
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            $newName = $thumbnailFile->getRandomName();
            $thumbnailFile->move(FCPATH . 'uploads/advokasi', $newName);
            $data['thumbnail'] = 'uploads/advokasi/' . $newName;
        }

        if ($settingModel->update(1, $data)) {
            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui pengaturan.');
    }
}
