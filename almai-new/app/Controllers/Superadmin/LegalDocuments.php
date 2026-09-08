<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\LegalDocumentModel;

class LegalDocuments extends BaseController
{
    protected $legalDocumentModel;

    public function __construct()
    {
        $this->legalDocumentModel = new LegalDocumentModel();
    }

    /**
     * Display list of legal documents
     */
    public function index()
    {
        $documents = $this->legalDocumentModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Dokumen Legal',
            'documents' => $documents,
        ];

        return view('superadmin/legal_documents/index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Dokumen Legal',
        ];

        return view('superadmin/legal_documents/create', $data);
    }

    /**
     * Store new document
     */
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required',
            'type' => 'required|in_list[perjanjian,risiko,other]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = $this->legalDocumentModel->generateSlug($title);

        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $this->request->getPost('content'),
            'type' => $this->request->getPost('type'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        try {
            if ($this->legalDocumentModel->insert($data)) {
                return redirect()->to('/superadmin/legal-documents')->with('success', 'Dokumen berhasil ditambahkan');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->legalDocumentModel->errors());
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to create legal document: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $document = $this->legalDocumentModel->find($id);

        if (!$document) {
            return redirect()->to('/superadmin/legal-documents')->with('error', 'Dokumen tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Dokumen Legal',
            'document' => $document,
        ];

        return view('superadmin/legal_documents/edit', $data);
    }

    /**
     * Update document
     */
    public function update($id)
    {
        $document = $this->legalDocumentModel->find($id);

        if (!$document) {
            return redirect()->to('/superadmin/legal-documents')->with('error', 'Dokumen tidak ditemukan');
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required',
            'type' => 'required|in_list[perjanjian,risiko,other]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');

        // Generate new slug if title changed
        if ($title !== $document['title']) {
            $slug = $this->legalDocumentModel->generateSlug($title, $id);
        } else {
            $slug = $document['slug'];
        }

        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $this->request->getPost('content'),
            'type' => $this->request->getPost('type'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        try {
            if ($this->legalDocumentModel->update($id, $data)) {
                return redirect()->to('/superadmin/legal-documents')->with('success', 'Dokumen berhasil diupdate');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->legalDocumentModel->errors());
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to update legal document: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Delete document
     */
    public function delete($id)
    {
        $document = $this->legalDocumentModel->find($id);

        if (!$document) {
            return redirect()->to('/superadmin/legal-documents')->with('error', 'Dokumen tidak ditemukan');
        }

        try {
            $this->legalDocumentModel->delete($id);
            return redirect()->to('/superadmin/legal-documents')->with('success', 'Dokumen berhasil dihapus');
        } catch (\Exception $e) {
            log_message('error', 'Failed to delete legal document: ' . $e->getMessage());
            return redirect()->to('/superadmin/legal-documents')->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Preview document
     */
    public function preview($id)
    {
        $document = $this->legalDocumentModel->find($id);

        if (!$document) {
            return redirect()->to('/superadmin/legal-documents')->with('error', 'Dokumen tidak ditemukan');
        }

        $data = [
            'title' => 'Preview: ' . $document['title'],
            'content' => $document['content'],
        ];

        return view('superadmin/legal_documents/preview', $data);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        $document = $this->legalDocumentModel->find($id);

        if (!$document) {
            return $this->response->setJSON(['success' => false, 'message' => 'Dokumen tidak ditemukan']);
        }

        try {
            $this->legalDocumentModel->update($id, ['is_active' => !$document['is_active']]);
            return $this->response->setJSON(['success' => true, 'message' => 'Status berhasil diubah']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status']);
        }
    }
}
