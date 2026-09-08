<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\KycSubmissionModel;
use App\Models\TransaksiModel;
use App\Models\PoinModel;
use App\Models\LevelModel;

class Users extends BaseController
{
    protected $userModel;
    protected $kycModel;
    protected $poinModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kycModel = new KycSubmissionModel();
        $this->poinModel = new PoinModel();
    }

    public function index()
    {
        $kegiatanType = $this->request->getGet('kegiatan_type');
        $search = $this->request->getGet('search');
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;

        $absensiModel = new \App\Models\AbsensiPesertaModel();
        $builder = $absensiModel->getWithUser();

        if ($kegiatanType && $kegiatanType !== 'all') {
            $builder->where('absensi_peserta.kegiatan_type', $kegiatanType);
        }

        if ($search) {
            $builder->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->orLike('users.phone', $search)
                ->groupEnd();
        }

        // Time Filter
        $timeFilter = $this->request->getGet('time_filter');
        if ($timeFilter) {
            switch ($timeFilter) {
                case 'today':
                    $builder->where('DATE(absensi_peserta.created_at)', date('Y-m-d'));
                    break;
                case 'this_week':
                    $builder->where('YEARWEEK(absensi_peserta.created_at, 1)', date('YW'));
                    break;
                case 'this_month':
                    $builder->where('MONTH(absensi_peserta.created_at)', date('m'))
                        ->where('YEAR(absensi_peserta.created_at)', date('Y'));
                    break;
                case 'this_year':
                    $builder->where('YEAR(absensi_peserta.created_at)', date('Y'));
                    break;
            }
        }

        $sort = $this->request->getGet('sort') ?? 'newest';
        if ($sort === 'oldest') {
            $builder->orderBy('absensi_peserta.created_at', 'ASC');
        } else {
            $builder->orderBy('absensi_peserta.created_at', 'DESC');
        }

        $totalUsersCount = $builder->countAllResults(false);
        $usersList = $builder->paginate($perPage, 'default', $page);
        $pager = $absensiModel->pager;

        // Fetch activity titles
        $db = \Config\Database::connect();
        foreach ($usersList as &$row) {
            $table = '';
            if ($row['kegiatan_type'] == 'seminar' || $row['kegiatan_type'] == 'seminar_fgd') $table = 'seminar_fgd';
            elseif ($row['kegiatan_type'] == 'pelatihan') $table = 'pelatihan';
            elseif ($row['kegiatan_type'] == 'kegiatan_lainnya') $table = 'kegiatan_lainnya';
            elseif ($row['kegiatan_type'] == 'konsultasi') $table = 'konsultasi';
            elseif ($row['kegiatan_type'] == 'expert_advisor') $table = 'expert_advisor';
            elseif ($row['kegiatan_type'] == 'signal') $table = 'signal';
            
            $row['kegiatan_name'] = '-';
            if ($table && $row['kegiatan_id']) {
                $activity = $db->table($table)->where('id', $row['kegiatan_id'])->get()->getRowArray();
                if ($activity) {
                    $row['kegiatan_name'] = $activity['judul'] ?? ($activity['nama'] ?? 'Unknown');
                }
            }
        }

        $grandTotal = $absensiModel->countAllResults();

        return view('laporan-kegiatan/users/index', [
            'title' => 'Data Klien & Absensi - Partnership Admin',
            'usersList' => $usersList,
            'pager' => $pager,
            'total' => $totalUsersCount,
            'page' => $page,
            'perPage' => $perPage,
            'grandTotal' => $grandTotal,
            'currentKegiatanType' => $kegiatanType,
            'currentSearch' => $search,
            'currentTimeFilter' => $timeFilter,
            'currentSort' => $sort,
            'activeMenu' => 'users'
        ]);
    }

    public function view($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/laporan-kegiatan/klien')->with('error', 'User tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $kycData = $db->table('kyc_submissions')->where('user_id', $id)->get()->getRowArray() ?: $db->table('user_data')->where('user_id', $id)->get()->getRowArray();

        $poinBalance = $this->poinModel->getUserBalance($id);
        $transaksiModel = new TransaksiModel();
        $totalTransaksi = $transaksiModel->where('user_id', $id)->countAllResults();
        $totalPembelian = $transaksiModel->where('user_id', $id)->where('status', 'confirmed')->selectSum('total')->first()['total'] ?? 0;

        $downlineIds = $this->userModel->getNetworkIds($id, 10);
        $downlineList = !empty($downlineIds) ? $this->userModel->whereIn('id', array_slice($downlineIds, 0, 100))->orderBy('created_at', 'DESC')->findAll() : [];
        
        $purchasedServices = $transaksiModel->getWithUser()
            ->where('transaksi.user_id', $id)
            ->whereIn('transaksi.status', ['confirmed', 'paid', 'refunded'])
            ->findAll();

        return view('laporan-kegiatan/users/view', [
            'title' => 'Detail User - Partnership Admin',
            'user' => $user,
            'kyc' => $kycData,
            'poinBalance' => $poinBalance,
            'totalTransaksi' => $totalTransaksi,
            'totalPembelian' => $totalPembelian,
            'downlineCount' => count($downlineIds),
            'downlineList' => $downlineList,
            'purchasedServices' => $purchasedServices,
            'activeMenu' => 'users'
        ]);
    }

    public function exportCsv()
    {
        $role = $this->request->getGet('role');
        $search = $this->request->getGet('search');
        $timeFilter = $this->request->getGet('time_filter');
        $sort = $this->request->getGet('sort') ?? 'newest';

        $builder = $this->userModel;

        if ($role && $role !== 'all') {
            $levelIds = \App\Models\LevelModel::getLevelIdsByRoleFilter($role);
            if ($levelIds === null && $role === 'admin') {
                // Laporan Kegiatan: 'admin' mencakup semua level staff/admin
                $levelIds = [
                    \App\Models\LevelModel::LEVEL_ADMIN,
                    \App\Models\LevelModel::LEVEL_ACCOUNTING,
                    \App\Models\LevelModel::LEVEL_SUPER_ADMIN,
                    \App\Models\LevelModel::LEVEL_PARTNERSHIP,
                    \App\Models\LevelModel::LEVEL_ADMIN_WPA,
                ];
            }
            if ($levelIds !== null) {
                $builder->whereIn('level_id', $levelIds);
            }
        }

        if ($search) {
            $builder->groupStart()->like('name', $search)->orLike('email', $search)->orLike('phone', $search)->groupEnd();
        }

        if ($timeFilter) {
            switch ($timeFilter) {
                case 'today': $builder->where('DATE(created_at)', date('Y-m-d')); break;
                case 'week': $builder->where('YEARWEEK(created_at, 1)', date('YW')); break;
                case 'month': $builder->where('MONTH(created_at)', date('m'))->where('YEAR(created_at)', date('Y')); break;
                case 'year': $builder->where('YEAR(created_at)', date('Y')); break;
            }
        }

        $builder->orderBy('created_at', $sort === 'oldest' ? 'ASC' : 'DESC');

        $users = $builder->findAll();

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nama', 'Email', 'No. HP', 'Level ID', 'Status', 'Terdaftar Pada']);

        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['name'],
                $user['email'],
                $user['phone'],
                $user['level_id'],
                $user['status'],
                $user['created_at']
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportPdf()
    {
        $kegiatanType = $this->request->getGet('kegiatan_type');
        $search = $this->request->getGet('search');
        $timeFilter = $this->request->getGet('time_filter');
        $sort = $this->request->getGet('sort') ?? 'newest';

        $absensiModel = new \App\Models\AbsensiPesertaModel();
        $builder = $absensiModel->getWithUser();

        if ($kegiatanType && $kegiatanType !== 'all') {
            $builder->where('absensi_peserta.kegiatan_type', $kegiatanType);
        }

        if ($search) {
            $builder->groupStart()->like('users.name', $search)->orLike('users.email', $search)->orLike('users.phone', $search)->groupEnd();
        }

        if ($timeFilter) {
            switch ($timeFilter) {
                case 'today': $builder->where('DATE(absensi_peserta.created_at)', date('Y-m-d')); break;
                case 'this_week': $builder->where('YEARWEEK(absensi_peserta.created_at, 1)', date('YW')); break;
                case 'this_month': $builder->where('MONTH(absensi_peserta.created_at)', date('m'))->where('YEAR(absensi_peserta.created_at)', date('Y')); break;
                case 'this_year': $builder->where('YEAR(absensi_peserta.created_at)', date('Y')); break;
            }
        }

        $builder->orderBy('absensi_peserta.created_at', $sort === 'oldest' ? 'ASC' : 'DESC');

        $usersList = $builder->findAll();
        
        $db = \Config\Database::connect();
        foreach ($usersList as &$row) {
            $table = '';
            if ($row['kegiatan_type'] == 'seminar' || $row['kegiatan_type'] == 'seminar_fgd') $table = 'seminar_fgd';
            elseif ($row['kegiatan_type'] == 'pelatihan') $table = 'pelatihan';
            elseif ($row['kegiatan_type'] == 'kegiatan_lainnya') $table = 'kegiatan_lainnya';
            elseif ($row['kegiatan_type'] == 'konsultasi') $table = 'konsultasi';
            elseif ($row['kegiatan_type'] == 'expert_advisor') $table = 'expert_advisor';
            elseif ($row['kegiatan_type'] == 'signal') $table = 'signal';
            
            $row['kegiatan_name'] = '-';
            if ($table && $row['kegiatan_id']) {
                $activity = $db->table($table)->where('id', $row['kegiatan_id'])->get()->getRowArray();
                if ($activity) {
                    $row['kegiatan_name'] = $activity['judul'] ?? ($activity['nama'] ?? 'Unknown');
                }
            }
        }

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);
        
        $html = view('laporan-kegiatan/users/pdf_export', ['usersList' => $usersList]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $filename = 'Laporan_Absensi_Peserta_' . date('Ymd_His') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }
}
