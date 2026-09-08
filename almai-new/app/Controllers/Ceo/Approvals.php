<?php

namespace App\Controllers\Ceo;

use App\Controllers\BaseController;
use App\Services\ExecutiveApprovalService;

class Approvals extends BaseController
{
    public function decide(int $id)
    {
        $rules = [
            'decision' => 'required|in_list[approved,rejected]',
            'reason' => 'required|min_length[5]|max_length[1000]',
            'version' => 'required|is_natural_no_zero',
        ];
        if (!$this->validate($rules)) {
            return redirect()->to('/ceo/dashboard#approvals')->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            (new ExecutiveApprovalService())->decide(
                $id,
                (string) $this->request->getPost('decision'),
                (string) $this->request->getPost('reason'),
                (int) $this->request->getPost('version'),
                (int) session()->get('userId')
            );
            return redirect()->to('/ceo/dashboard#approvals')->with('success', 'Keputusan approval berhasil disimpan dan dicatat.');
        } catch (\Throwable $e) {
            log_message('warning', 'CEO approval failed: ' . $e->getMessage());
            return redirect()->to('/ceo/dashboard#approvals')->with('error', $e->getMessage());
        }
    }

    public function batch()
    {
        $ids = $this->request->getPost('approval_ids');
        if (!is_array($ids)) $ids = [];
        if (!$this->validate(['decision' => 'required|in_list[approved,rejected]', 'reason' => 'required|min_length[5]|max_length[1000]'])) {
            return redirect()->to('/ceo/dashboard#approvals')->with('error', implode(' ', $this->validator->getErrors()));
        }
        try {
            $results = (new ExecutiveApprovalService())->decideBatch($ids, (string) $this->request->getPost('decision'), (string) $this->request->getPost('reason'), (int) session()->get('userId'));
            return redirect()->to('/ceo/dashboard#approvals')->with('success', count($results) . ' approval berhasil diproses secara atomik.');
        } catch (\Throwable $e) {
            log_message('warning', 'CEO batch approval failed: ' . $e->getMessage());
            return redirect()->to('/ceo/dashboard#approvals')->with('error', $e->getMessage());
        }
    }
}
