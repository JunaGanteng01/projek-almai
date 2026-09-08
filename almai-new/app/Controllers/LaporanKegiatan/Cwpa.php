<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\CwpaModel;

class Cwpa extends BaseController
{
    protected $cwpaModel;

    public function __construct()
    {
        $this->cwpaModel = new CwpaModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->cwpaModel->select('cwpa.*, users.email as user_email')
            ->join('users', 'users.id = cwpa.user_id', 'left');

        if ($search) {
            $builder->groupStart()
                ->like('cwpa.name', $search)
                ->orLike('cwpa.specialty', $search)
                ->orLike('users.email', $search)
                ->groupEnd();
        }

        if ($status && $status !== 'all') {
            $builder->where('cwpa.status', $status);
        }

        $cwpaList = $builder->orderBy('cwpa.id', 'DESC')->paginate(20, 'cwpa');
        $pager = $this->cwpaModel->pager;

        $statsModel = new CwpaModel();
        $stats = [
            'total' => $statsModel->countAllResults(),
            'active' => (new CwpaModel())->where('status', 'active')->countAllResults(),
            'inactive' => (new CwpaModel())->where('status', 'inactive')->countAllResults(),
            'avg_rating' => (new CwpaModel())->selectAvg('rating')->first()['rating'] ?? 0
        ];

        return view('laporan-kegiatan/cwpa/index', [
            'title' => 'Data CWPA',
            'activeMenu' => 'cwpa',
            'cwpaList' => $cwpaList,
            'pager' => $pager,
            'search' => $search,
            'currentStatus' => $status,
            'stats' => $stats
        ]);
    }

    public function view($id)
    {
        $cwpa = $this->cwpaModel->select('cwpa.*, users.email as user_email, users.phone as user_phone, users.name as user_real_name')
            ->join('users', 'users.id = cwpa.user_id', 'left')
            ->find($id);

        if (!$cwpa) {
            return redirect()->to('/laporan-kegiatan/cwpa')->with('error', 'CWPA tidak ditemukan');
        }

        return view('laporan-kegiatan/cwpa/view', [
            'title' => 'Detail CWPA',
            'activeMenu' => 'cwpa',
            'cwpa' => $cwpa
        ]);
    }

    public function exportCsv()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->cwpaModel->select('cwpa.*, users.email as user_email')
            ->join('users', 'users.id = cwpa.user_id', 'left');

        if ($search) {
            $builder->groupStart()
                ->like('cwpa.name', $search)
                ->orLike('cwpa.specialty', $search)
                ->orLike('users.email', $search)
                ->groupEnd();
        }

        if ($status && $status !== 'all') {
            $builder->where('cwpa.status', $status);
        }

        $cwpaList = $builder->orderBy('cwpa.id', 'DESC')->findAll();

        $filename = 'cwpa_export_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Nama CWPA', 'NIK CWPA', 'Almai Pendampingan', 'Bursa ICDX Sertifikasi Multilateral', 'LPK Sertifikasi Pelatihan PBK', 'BNSP Sertifikasi Kompetensi', 'Status', 'Keterangan']);

        $no = 1;
        foreach ($cwpaList as $cwpa) {
            fputcsv($output, [
                $no++,
                $cwpa['name'],
                $cwpa['nik_cwpa'],
                $cwpa['almai_pendampingan'],
                $cwpa['bursa_icdx_sertifikasi_multilateral'],
                $cwpa['lpk_sertifikasi_pelatihan_pbk'],
                $cwpa['bnsp_sertifikasi_kompetensi'],
                $cwpa['status'] === 'active' ? 'Aktif' : 'Tidak Aktif',
                $cwpa['keterangan']
            ]);
        }

        fclose($output);
        exit;
    }

    public function create()
    {
        return view('laporan-kegiatan/cwpa/form', [
            'title' => 'Tambah Data CWPA',
            'activeMenu' => 'cwpa'
        ]);
    }

    public function store()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'specialty' => $this->request->getPost('specialty'),
            'university' => $this->request->getPost('university'),
            'batch' => $this->request->getPost('batch'),
            'current_phase' => $this->request->getPost('current_phase') ?: 1,
            'status' => $this->request->getPost('status') ?? 'active',
            'nik_cwpa' => $this->request->getPost('nik_cwpa'),
            'almai_pendampingan' => $this->request->getPost('almai_pendampingan'),
            'bursa_icdx_sertifikasi_multilateral' => $this->request->getPost('bursa_icdx_sertifikasi_multilateral'),
            'lpk_sertifikasi_pelatihan_pbk' => $this->request->getPost('lpk_sertifikasi_pelatihan_pbk'),
            'bnsp_sertifikasi_kompetensi' => $this->request->getPost('bnsp_sertifikasi_kompetensi'),
            'keterangan' => $this->request->getPost('keterangan'),
            'user_id' => 0 
        ];

        $fileFields = [
            'almai_pendampingan_file',
            'bursa_icdx_sertifikasi_multilateral_file',
            'lpk_sertifikasi_pelatihan_pbk_file',
            'bnsp_sertifikasi_kompetensi_file'
        ];

        foreach ($fileFields as $field) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/cwpa', $newName);
                $data[$field] = 'uploads/cwpa/' . $newName;
            }
        }

        $this->cwpaModel->insert($data);

        return redirect()->to('/laporan-kegiatan/cwpa')->with('success', 'Data CWPA berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $cwpa = $this->cwpaModel->find($id);

        if (!$cwpa) {
            return redirect()->to('/laporan-kegiatan/cwpa')->with('error', 'Data CWPA tidak ditemukan.');
        }

        return view('laporan-kegiatan/cwpa/form', [
            'title' => 'Edit Data CWPA',
            'activeMenu' => 'cwpa',
            'cwpa' => $cwpa
        ]);
    }

    public function update($id)
    {
        $cwpa = $this->cwpaModel->find($id);

        if (!$cwpa) {
            return redirect()->to('/laporan-kegiatan/cwpa')->with('error', 'Data CWPA tidak ditemukan.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'specialty' => $this->request->getPost('specialty'),
            'university' => $this->request->getPost('university'),
            'batch' => $this->request->getPost('batch'),
            'current_phase' => $this->request->getPost('current_phase') ?: 1,
            'status' => $this->request->getPost('status') ?? 'active',
            'nik_cwpa' => $this->request->getPost('nik_cwpa'),
            'almai_pendampingan' => $this->request->getPost('almai_pendampingan'),
            'bursa_icdx_sertifikasi_multilateral' => $this->request->getPost('bursa_icdx_sertifikasi_multilateral'),
            'lpk_sertifikasi_pelatihan_pbk' => $this->request->getPost('lpk_sertifikasi_pelatihan_pbk'),
            'bnsp_sertifikasi_kompetensi' => $this->request->getPost('bnsp_sertifikasi_kompetensi'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $fileFields = [
            'almai_pendampingan_file',
            'bursa_icdx_sertifikasi_multilateral_file',
            'lpk_sertifikasi_pelatihan_pbk_file',
            'bnsp_sertifikasi_kompetensi_file'
        ];

        foreach ($fileFields as $field) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/cwpa', $newName);
                $data[$field] = 'uploads/cwpa/' . $newName;
                
                // Hapus file lama jika ada
                if (!empty($cwpa[$field]) && file_exists(FCPATH . $cwpa[$field])) {
                    unlink(FCPATH . $cwpa[$field]);
                }
            }
        }
        
        // Sync to phase_certificates and phase_certificate_numbers
        $phaseCertificates = !empty($cwpa['phase_certificates']) ? json_decode($cwpa['phase_certificates'], true) : [];
        $phaseCertificateNumbers = !empty($cwpa['phase_certificate_numbers']) ? json_decode($cwpa['phase_certificate_numbers'], true) : [];

        if (array_key_exists('almai_pendampingan', $data)) $phaseCertificateNumbers[1] = $data['almai_pendampingan'];
        if (array_key_exists('bursa_icdx_sertifikasi_multilateral', $data)) $phaseCertificateNumbers[2] = $data['bursa_icdx_sertifikasi_multilateral'];
        if (array_key_exists('lpk_sertifikasi_pelatihan_pbk', $data)) $phaseCertificateNumbers[3] = $data['lpk_sertifikasi_pelatihan_pbk'];
        if (array_key_exists('bnsp_sertifikasi_kompetensi', $data)) $phaseCertificateNumbers[4] = $data['bnsp_sertifikasi_kompetensi'];

        if (array_key_exists('almai_pendampingan_file', $data)) $phaseCertificates[1] = $data['almai_pendampingan_file'];
        if (array_key_exists('bursa_icdx_sertifikasi_multilateral_file', $data)) $phaseCertificates[2] = $data['bursa_icdx_sertifikasi_multilateral_file'];
        if (array_key_exists('lpk_sertifikasi_pelatihan_pbk_file', $data)) $phaseCertificates[3] = $data['lpk_sertifikasi_pelatihan_pbk_file'];
        if (array_key_exists('bnsp_sertifikasi_kompetensi_file', $data)) $phaseCertificates[4] = $data['bnsp_sertifikasi_kompetensi_file'];

        $data['phase_certificates'] = !empty($phaseCertificates) ? json_encode($phaseCertificates) : null;
        $data['phase_certificate_numbers'] = !empty($phaseCertificateNumbers) ? json_encode($phaseCertificateNumbers) : null;

        $this->cwpaModel->update($id, $data);

        return redirect()->to('/laporan-kegiatan/cwpa')->with('success', 'Data CWPA berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->cwpaModel->delete($id);
        return redirect()->to('/laporan-kegiatan/cwpa')->with('success', 'Data CWPA berhasil dihapus.');
    }
}
