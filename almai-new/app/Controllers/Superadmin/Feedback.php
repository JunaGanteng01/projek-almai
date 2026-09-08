<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use CodeIgniter\Database\Exceptions\DatabaseException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Feedback extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('feedbacks');
        $builder->select('feedbacks.*, users.name, users.email, users.phone');
        $builder->join('users', 'users.id = feedbacks.user_id', 'left');
        
        $search = $this->request->getGet('search');
        if (!empty($search)) {
            $builder->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->orLike('feedbacks.message', $search)
                ->groupEnd();
        }
        
        $builder->orderBy('feedbacks.created_at', 'DESC');
        
        $feedbacks = $builder->get()->getResultArray();

        $data = [
            'title' => 'Data Feedback - Superadmin',
            'feedbacks' => $feedbacks,
            'search' => $search,
            'pageTitle' => 'Feedback User',
            'pageSubtitle' => 'Kelola dan lihat feedback, saran, serta kritik dari user'
        ];

        return view('superadmin/feedback/index', $data);
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        try {
            $db->table('feedbacks')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Feedback berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus feedback.');
        }
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('feedbacks');
        $builder->select('feedbacks.*, users.name, users.email, users.phone');
        $builder->join('users', 'users.id = feedbacks.user_id', 'left');
        
        $search = $this->request->getGet('search');
        if (!empty($search)) {
            $builder->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->orLike('feedbacks.message', $search)
                ->groupEnd();
        }
        
        $builder->orderBy('feedbacks.created_at', 'DESC');
        $feedbacks = $builder->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Nama User');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Phone');
        $sheet->setCellValue('F1', 'Rating');
        $sheet->setCellValue('G1', 'Pesan (Kritik & Saran)');

        // Format Header
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FFA0A0A0');

        $row = 2;
        $no = 1;
        foreach ($feedbacks as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d M Y, H:i', strtotime($item['created_at'])));
            $sheet->setCellValue('C' . $row, $item['name']);
            $sheet->setCellValue('D' . $row, $item['email']);
            $sheet->setCellValue('E' . $row, $item['phone'] ?? '-');
            $sheet->setCellValue('F' . $row, $item['rating']);
            $sheet->setCellValue('G' . $row, $item['message']);
            
            // Format wrap text for message column
            $sheet->getStyle('G' . $row)->getAlignment()->setWrapText(true);
            
            $row++;
        }

        // Auto width
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Feedback_User_' . date('Y-m-d_H-i') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
