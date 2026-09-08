<?php

namespace App\Controllers\Wpa;

use App\Controllers\BaseController;
use App\Models\SeminarModel;
use App\Models\PelatihanModel;
use App\Models\SignalModel;
use App\Models\KonsultasiModel;
use App\Models\ExpertAdvisorModel;
use App\Models\KegiatanLainnyaModel;
use App\Models\AbsensiPesertaModel;
use App\Models\CwpaModel;

class Absensi extends BaseController
{
    private function getWpaId()
    {
        return $this->session->get('wpaId');
    }

    public function index()
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/wpa/dashboard');

        $seminarModel = new SeminarModel();
        $pelatihanModel = new PelatihanModel();
        $signalModel = new SignalModel();
        $konsultasiModel = new KonsultasiModel();
        $eaModel = new ExpertAdvisorModel();
        $lainnyaModel = new KegiatanLainnyaModel();

        $q = $this->request->getGet('q');
        $tipe = $this->request->getGet('tipe');

        $all_activities = [];
        
        $addActivity = function($items, $type) use (&$all_activities) {
            foreach ($items as $item) {
                $item['tipe'] = $type;
                if ($type == 'Seminar FGD' || $type == 'Pelatihan Simulasi') $item['nama'] = $item['judul'];
                elseif ($type == 'Signal' || $type == 'Konsultasi') $item['nama'] = $item['keterangan'] ?? $type;
                elseif ($type == 'Expert Advisor') $item['nama'] = $item['nama_layanan'];
                elseif ($type == 'Kegiatan Lainnya') $item['nama'] = $item['nama_kegiatan'];
                
                $item['kegiatan_type_id'] = strtolower(str_replace(' ', '_', $type));
                
                $all_activities[] = $item;
            }
        };

        if (empty($tipe) || $tipe == 'seminar_fgd') $addActivity($seminarModel->where('kode_qr IS NOT NULL')->where('wpa_id', $wpaId)->findAll(), 'Seminar FGD');
        if (empty($tipe) || $tipe == 'pelatihan_simulasi') $addActivity($pelatihanModel->where('kode_qr IS NOT NULL')->where('wpa_id', $wpaId)->findAll(), 'Pelatihan Simulasi');
        if (empty($tipe) || $tipe == 'signals') $addActivity($signalModel->where('kode_qr IS NOT NULL')->where('wpa_id', $wpaId)->findAll(), 'Signal');
        if (empty($tipe) || $tipe == 'konsultasi') $addActivity($konsultasiModel->where('kode_qr IS NOT NULL')->where('wpa_id', $wpaId)->findAll(), 'Konsultasi');
        if (empty($tipe) || $tipe == 'expert_advisor') $addActivity($eaModel->where('kode_qr IS NOT NULL')->where('wpa_id', $wpaId)->findAll(), 'Expert Advisor');
        if (empty($tipe) || $tipe == 'kegiatan_lainnya') $addActivity($lainnyaModel->where('kode_qr IS NOT NULL')->where('wpa_id', $wpaId)->findAll(), 'Kegiatan Lainnya');

        $filtered = [];
        foreach ($all_activities as $item) {
            if (!empty($q)) {
                $searchStr = strtolower($item['nama'] ?? '');
                if (strpos($searchStr, strtolower($q)) === false) continue;
            }
            $filtered[] = $item;
        }

        usort($filtered, function($a, $b) {
            $timeA = strtotime($a['created_at'] ?? '2000-01-01');
            $timeB = strtotime($b['created_at'] ?? '2000-01-01');
            return $timeB <=> $timeA;
        });

        $data['all_activities'] = $filtered;
        $data['filters'] = ['q' => $q, 'tipe' => $tipe];
        $data['title'] = 'Absensi Kegiatan';
        $data['activeMenu'] = 'absensi';
        
        return view('wpa/absensi/index', $data);
    }

    public function create()
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/wpa/dashboard');

        $cwpaModel = new CwpaModel();
        $data['cwpa_list'] = $cwpaModel->select('cwpa.*, users.name as user_name')->join('users', 'users.id = cwpa.user_id')->findAll();
        $data['title'] = 'Buat Absensi';
        $data['activeMenu'] = 'absensi';

        return view('wpa/absensi/create', $data);
    }

    public function store()
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/wpa/dashboard');

        $kegiatan = $this->request->getPost('kegiatan');
        
        $kodeQr = 'ABS-' . strtoupper(uniqid());
        $linkAbsensi = base_url('absensi/checkin/' . $kodeQr);

        $cwpaId = $this->request->getPost('cwpa_id');
        $commonData = [
            'wpa_id' => $wpaId,
            'cwpa_id' => empty($cwpaId) ? null : $cwpaId,
            'produk' => $this->request->getPost('produk'),
            'tanggal' => $this->request->getPost('tanggal_kegiatan'),
            'kode_qr' => $kodeQr,
            'link_absensi' => $linkAbsensi,
            'expired_link_kode_qr' => $this->request->getPost('expired_link_kode_qr'),
            'created_by_wpa_id' => $wpaId,
        ];

        switch ($kegiatan) {
            case 'seminar_fgd':
                $model = new SeminarModel();
                $data = array_merge($commonData, [
                    'judul' => $this->request->getPost('judul'),
                    'jml_peserta' => $this->request->getPost('jumlah_klien'),
                    'lokasi' => $this->request->getPost('lokasi'),
                    'topik' => $this->request->getPost('topik'),
                ]);
                break;
            case 'pelatihan_simulasi':
                $model = new PelatihanModel();
                $data = array_merge($commonData, [
                    'judul' => $this->request->getPost('judul'),
                    'jml_peserta' => $this->request->getPost('jumlah_klien'),
                    'lokasi' => $this->request->getPost('lokasi'),
                    'topik' => $this->request->getPost('topik'),
                ]);
                break;
            case 'signals':
                $model = new SignalModel();
                $data = array_merge($commonData, [
                    'jml_peserta' => $this->request->getPost('jumlah_klien'),
                    'media' => $this->request->getPost('lokasi'),
                    'keterangan' => $this->request->getPost('judul') . ' - ' . $this->request->getPost('topik'),
                    'jml_nasihat' => 0,
                ]);
                break;
            case 'konsultasi':
                $model = new KonsultasiModel();
                $data = array_merge($commonData, [
                    'nama_klien' => 'Klien (Estimasi: '.$this->request->getPost('jumlah_klien').')',
                    'media' => $this->request->getPost('lokasi'),
                    'keterangan' => $this->request->getPost('judul') . ' - ' . $this->request->getPost('topik'),
                    'jml_nasihat' => 0,
                ]);
                break;
            case 'expert_advisor':
                $model = new ExpertAdvisorModel();
                $data = array_merge($commonData, [
                    'nama_layanan' => $this->request->getPost('judul'),
                    'penjelasan_layanan' => $this->request->getPost('topik'),
                    'jml_klien' => $this->request->getPost('jumlah_klien'),
                    'keterangan' => 'Lokasi: ' . $this->request->getPost('lokasi'),
                ]);
                break;
            case 'kegiatan_lainnya':
                $model = new KegiatanLainnyaModel();
                $data = array_merge($commonData, [
                    'nama_kegiatan' => $this->request->getPost('judul'),
                    'jml_klien' => $this->request->getPost('jumlah_klien'),
                    'media' => $this->request->getPost('lokasi'),
                    'keterangan' => $this->request->getPost('topik'),
                    'jml_nasihat' => 0,
                ]);
                break;
            default:
                return redirect()->back()->withInput()->with('error', 'Jenis kegiatan tidak valid');
        }

        if ($model->insert($data)) {
            return redirect()->to('/wpa/dashboard/absensi')->with('success', 'Absensi berhasil dibuat. Link: ' . $linkAbsensi);
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data absensi');
        }
    }

    public function delete($kegiatanType, $id)
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/wpa/dashboard');

        $model = null;
        switch ($kegiatanType) {
            case 'Seminar FGD':
            case 'seminar_fgd':
                $model = new SeminarModel();
                break;
            case 'Pelatihan Simulasi':
            case 'pelatihan_simulasi':
                $model = new PelatihanModel();
                break;
            case 'Signal':
            case 'signals':
                $model = new SignalModel();
                break;
            case 'Konsultasi':
            case 'konsultasi':
                $model = new KonsultasiModel();
                break;
            case 'Expert Advisor':
            case 'expert_advisor':
                $model = new ExpertAdvisorModel();
                break;
            case 'Kegiatan Lainnya':
            case 'kegiatan_lainnya':
                $model = new KegiatanLainnyaModel();
                break;
            default:
                return redirect()->back()->with('error', 'Jenis kegiatan tidak valid');
        }

        // Verify ownership
        $kegiatan = $model->find($id);
        if (!$kegiatan || $kegiatan['wpa_id'] != $wpaId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        // Check if any peserta has checked in
        if ($this->hasAbsensiPeserta($kegiatanType, $id)) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus absensi karena sudah ada peserta yang absen.');
        }

        if ($model->delete($id)) {
            return redirect()->to('/wpa/dashboard/absensi')->with('success', 'Data absensi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus data absensi.');
    }

    private function hasAbsensiPeserta($kegiatanType, $kegiatanId)
    {
        $absensiPesertaModel = new AbsensiPesertaModel();
        $mappedType = strtolower(str_replace(' ', '_', $kegiatanType));
        if ($mappedType == 'seminar_fgd') $mappedType = 'seminar';
        elseif ($mappedType == 'pelatihan_simulasi') $mappedType = 'pelatihan';

        return $absensiPesertaModel->where('kegiatan_type', $mappedType)
                                   ->where('kegiatan_id', $kegiatanId)
                                   ->countAllResults() > 0;
    }

    public function detail($kegiatanType, $id)
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/wpa/dashboard');

        $model = null;
        $mappedType = strtolower(str_replace(' ', '_', $kegiatanType));
        $dbType = $mappedType;

        switch ($mappedType) {
            case 'seminar_fgd':
            case 'seminar':
                $model = new SeminarModel();
                $dbType = 'seminar';
                break;
            case 'pelatihan_simulasi':
            case 'pelatihan':
                $model = new PelatihanModel();
                $dbType = 'pelatihan';
                break;
            case 'signal':
            case 'signals':
                $model = new SignalModel();
                $dbType = 'signals';
                break;
            case 'konsultasi':
                $model = new KonsultasiModel();
                break;
            case 'expert_advisor':
                $model = new ExpertAdvisorModel();
                break;
            case 'kegiatan_lainnya':
                $model = new KegiatanLainnyaModel();
                break;
            default:
                return redirect()->back()->with('error', 'Jenis kegiatan tidak valid');
        }

        $kegiatan = $model->find($id);
        if (!$kegiatan || $kegiatan['wpa_id'] != $wpaId) {
            return redirect()->to('/wpa/dashboard/absensi')->with('error', 'Kegiatan tidak ditemukan atau bukan milik Anda');
        }

        if ($mappedType == 'seminar_fgd' || $mappedType == 'seminar' || $mappedType == 'pelatihan_simulasi' || $mappedType == 'pelatihan') $kegiatan['nama'] = $kegiatan['judul'];
        elseif ($mappedType == 'signal' || $mappedType == 'signals' || $mappedType == 'konsultasi') $kegiatan['nama'] = $kegiatan['keterangan'] ?? 'Kegiatan';
        elseif ($mappedType == 'expert_advisor') $kegiatan['nama'] = $kegiatan['nama_layanan'];
        elseif ($mappedType == 'kegiatan_lainnya') $kegiatan['nama'] = $kegiatan['nama_kegiatan'];

        $absensiPesertaModel = new AbsensiPesertaModel();
        
        $peserta = $absensiPesertaModel->select('absensi_peserta.*, users.name as user_name, users.email')
                                       ->join('users', 'users.id = absensi_peserta.user_id')
                                       ->where('kegiatan_type', $dbType)
                                       ->where('kegiatan_id', $id)
                                       ->orderBy('created_at', 'DESC')
                                       ->findAll();

        $data = [
            'title' => 'Detail Absensi - ' . $kegiatan['nama'],
            'kegiatan' => $kegiatan,
            'kegiatanType' => ucwords(str_replace('_', ' ', $kegiatanType)),
            'peserta' => $peserta,
            'activeMenu' => 'absensi'
        ];

        return view('wpa/absensi/detail', $data);
    }

    public function exportCsv($kegiatanType, $id)
    {
        $wpaId = $this->getWpaId();
        if (!$wpaId) return redirect()->to('/wpa/dashboard');

        $model = null;
        $mappedType = strtolower(str_replace(' ', '_', $kegiatanType));
        $dbType = $mappedType;

        switch ($mappedType) {
            case 'seminar_fgd':
            case 'seminar':
                $model = new SeminarModel();
                $dbType = 'seminar';
                break;
            case 'pelatihan_simulasi':
            case 'pelatihan':
                $model = new PelatihanModel();
                $dbType = 'pelatihan';
                break;
            case 'signal':
            case 'signals':
                $model = new SignalModel();
                $dbType = 'signals';
                break;
            case 'konsultasi':
                $model = new KonsultasiModel();
                break;
            case 'expert_advisor':
                $model = new ExpertAdvisorModel();
                break;
            case 'kegiatan_lainnya':
                $model = new KegiatanLainnyaModel();
                break;
            default:
                return redirect()->back()->with('error', 'Jenis kegiatan tidak valid');
        }

        $kegiatan = $model->find($id);
        if (!$kegiatan || $kegiatan['wpa_id'] != $wpaId) {
            return redirect()->to('/wpa/dashboard/absensi')->with('error', 'Kegiatan tidak ditemukan atau bukan milik Anda');
        }

        if ($mappedType == 'seminar_fgd' || $mappedType == 'seminar' || $mappedType == 'pelatihan_simulasi' || $mappedType == 'pelatihan') $kegiatan['nama'] = $kegiatan['judul'];
        elseif ($mappedType == 'signal' || $mappedType == 'signals' || $mappedType == 'konsultasi') $kegiatan['nama'] = $kegiatan['keterangan'] ?? 'Kegiatan';
        elseif ($mappedType == 'expert_advisor') $kegiatan['nama'] = $kegiatan['nama_layanan'];
        elseif ($mappedType == 'kegiatan_lainnya') $kegiatan['nama'] = $kegiatan['nama_kegiatan'];

        $absensiPesertaModel = new AbsensiPesertaModel();
        
        $peserta = $absensiPesertaModel->select('absensi_peserta.*, users.name as user_name, users.email')
                                       ->join('users', 'users.id = absensi_peserta.user_id')
                                       ->where('kegiatan_type', $dbType)
                                       ->where('kegiatan_id', $id)
                                       ->orderBy('created_at', 'DESC')
                                       ->findAll();

        $filename = 'Absensi_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $kegiatan['nama']) . '_' . date('Ymd') . '.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; "); 
        
        $file = fopen('php://output', 'w');
        
        $header = ["No", "Nama Peserta", "Email", "Waktu Absen"];
        fputcsv($file, $header);

        $no = 1;
        foreach ($peserta as $p) {
            fputcsv($file, [
                $no++, 
                $p['user_name'], 
                $p['email'], 
                date('d M Y, H:i:s', strtotime($p['created_at']))
            ]);
        }
        
        fclose($file);
        exit;
    }
}
