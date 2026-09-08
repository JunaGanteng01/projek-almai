<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\FaqModel;

class FaqManagement extends BaseController
{
    protected $faqModel;

    public function __construct()
    {
        $this->faqModel = new FaqModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola FAQ',
            'activeMenu' => 'faq',
            'faqs' => $this->faqModel->orderBy('id', 'ASC')->findAll()
        ];

        return view('superadmin/faq/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah FAQ Baru',
            'activeMenu' => 'faq'
        ];

        return view('superadmin/faq/create', $data);
    }

    public function store()
    {
        $rules = [
            'question' => 'required',
            'answer'   => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->faqModel->insert([
            'question' => $this->request->getPost('question'),
            'answer'   => $this->request->getPost('answer')
        ]);

        return redirect()->to(base_url('superadmin/faq'))->with('success', 'FAQ berhasil ditambahkan');
    }

    public function edit($id)
    {
        $faq = $this->faqModel->find($id);
        if (!$faq) {
            return redirect()->to(base_url('superadmin/faq'))->with('error', 'FAQ tidak ditemukan');
        }

        $data = [
            'title' => 'Edit FAQ',
            'activeMenu' => 'faq',
            'faq' => $faq
        ];

        return view('superadmin/faq/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'question' => 'required',
            'answer'   => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->faqModel->update($id, [
            'question' => $this->request->getPost('question'),
            'answer'   => $this->request->getPost('answer')
        ]);

        return redirect()->to(base_url('superadmin/faq'))->with('success', 'FAQ berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->faqModel->delete($id);
        return redirect()->to(base_url('superadmin/faq'))->with('success', 'FAQ berhasil dihapus');
    }
}
