<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\SeminarModel;
use App\Models\PelatihanModel;
use App\Models\SignalModel;
use App\Models\KonsultasiModel;
use App\Models\ExpertAdvisorModel;
use App\Models\KegiatanLainnyaModel;
use App\Models\WpaModel;
use App\Models\CwpaModel;

class Absensi extends BaseController
{
    public function index()
    {
        $seminarModel = new SeminarModel();
        $pelatihanModel = new PelatihanModel();
        $signalModel = new SignalModel();
        $konsultasiModel = new KonsultasiModel();
        $eaModel = new ExpertAdvisorModel();
        $lainnyaModel = new KegiatanLainnyaModel();
        
        $wpaModel = new WpaModel();
        $cwpaModel = new CwpaModel();

        // Get filters
        $q = $this->request->getGet('q');
        $tipe = $this->request->getGet('tipe');
        $wpa_id = $this->request->getGet('wpa_id');
        $cwpa_id = $this->request->getGet('cwpa_id');

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

        if (empty($tipe) || $tipe == 'seminar_fgd') $addActivity($seminarModel->where('kode_qr IS NOT NULL')->findAll(), 'Seminar FGD');
        if (empty($tipe) || $tipe == 'pelatihan_simulasi') $addActivity($pelatihanModel->where('kode_qr IS NOT NULL')->findAll(), 'Pelatihan Simulasi');
        if (empty($tipe) || $tipe == 'signals') $addActivity($signalModel->where('kode_qr IS NOT NULL')->findAll(), 'Signal');
        if (empty($tipe) || $tipe == 'konsultasi') $addActivity($konsultasiModel->where('kode_qr IS NOT NULL')->findAll(), 'Konsultasi');
        if (empty($tipe) || $tipe == 'expert_advisor') $addActivity($eaModel->where('kode_qr IS NOT NULL')->findAll(), 'Expert Advisor');
        if (empty($tipe) || $tipe == 'kegiatan_lainnya') $addActivity($lainnyaModel->where('kode_qr IS NOT NULL')->findAll(), 'Kegiatan Lainnya');

        // Apply filters in PHP
        $filtered = [];
        foreach ($all_activities as $item) {
            if (!empty($wpa_id) && $item['wpa_id'] != $wpa_id) continue;
            if (!empty($cwpa_id) && $item['cwpa_id'] != $cwpa_id) continue;
            if (!empty($q)) {
                $searchStr = strtolower($item['nama'] ?? '');
                if (strpos($searchStr, strtolower($q)) === false) continue;
            }
            $filtered[] = $item;
        }

        // Sort by created_at desc
        usort($filtered, function($a, $b) {
            $timeA = strtotime($a['created_at'] ?? '2000-01-01');
            $timeB = strtotime($b['created_at'] ?? '2000-01-01');
            return $timeB <=> $timeA;
        });

        $data['all_activities'] = $filtered;
        $data['wpa_list'] = $wpaModel->findAll();
        $data['cwpa_list'] = $cwpaModel->select('cwpa.*, users.name as user_name')->join('users', 'users.id = cwpa.user_id')->findAll();
        $data['filters'] = ['q' => $q, 'tipe' => $tipe, 'wpa_id' => $wpa_id, 'cwpa_id' => $cwpa_id];
        $data['title'] = 'Daftar Absensi';
        $data['activeMenu'] = 'absensi';
        
        return view('laporan-kegiatan/absensi/index', $data);
    }

    public function create()
    {
        $wpaModel = new WpaModel();
        $cwpaModel = new CwpaModel();

        $data['wpa_list'] = $wpaModel->findAll();
        $data['cwpa_list'] = $cwpaModel->select('cwpa.*, users.name as user_name')->join('users', 'users.id = cwpa.user_id')->findAll();
        $templateModel = new \App\Models\WhatsappTemplateModel();
        $data['whatsapp_templates'] = $templateModel->findAll();
        $data['title'] = 'Buat Absensi';
        $data['activeMenu'] = 'absensi';

        return view('laporan-kegiatan/absensi/create', $data);
    }

    public function store()
    {
        $kegiatan = $this->request->getPost('kegiatan');
        
        $kodeQr = 'ABS-' . strtoupper(uniqid());
        $linkAbsensi = base_url('absensi/checkin/' . $kodeQr);

        $cwpaId = $this->request->getPost('cwpa_id');
        $commonData = [
            'wpa_id' => $this->request->getPost('wpa_id'),
            'cwpa_id' => empty($cwpaId) ? null : $cwpaId,
            'produk' => $this->request->getPost('produk'),
            'tanggal' => $this->request->getPost('tanggal_kegiatan'),
            'kode_qr' => $kodeQr,
            'link_absensi' => $linkAbsensi,
            'expired_link_kode_qr' => $this->request->getPost('expired_link_kode_qr'),
            'format_notif_wa' => $this->request->getPost('format_notif_wa') ?: null,
        ];

        switch ($kegiatan) {
            case 'seminar_fgd':
                $model = new SeminarModel();
                $data = array_merge($commonData, [
                    'judul' => $this->request->getPost('judul'),
                    'jml_peserta' => $this->request->getPost('jumlah_klien'),
                    'lokasi' => $this->request->getPost('lokasi'),
                    'topik' => $this->request->getPost('topik'),
                    'keterangan' => $this->request->getPost('keterangan'),
                ]);
                break;
            case 'pelatihan_simulasi':
                $model = new PelatihanModel();
                $data = array_merge($commonData, [
                    'judul' => $this->request->getPost('judul'),
                    'jml_peserta' => $this->request->getPost('jumlah_klien'),
                    'lokasi' => $this->request->getPost('lokasi'),
                    'topik' => $this->request->getPost('topik'),
                    'keterangan' => $this->request->getPost('keterangan'),
                ]);
                break;
            case 'signals':
                $model = new SignalModel();
                $data = array_merge($commonData, [
                    'jml_peserta' => $this->request->getPost('jumlah_klien'),
                    'media' => $this->request->getPost('lokasi'),
                    'keterangan' => $this->request->getPost('keterangan'),
                    'jml_nasihat' => 0,
                ]);
                break;
            case 'konsultasi':
                $model = new KonsultasiModel();
                $data = array_merge($commonData, [
                    'nama_klien' => 'Klien (Estimasi: '.$this->request->getPost('jumlah_klien').')',
                    'media' => $this->request->getPost('lokasi'),
                    'keterangan' => $this->request->getPost('keterangan'),
                    'jml_nasihat' => 0,
                ]);
                break;
            case 'expert_advisor':
                $model = new ExpertAdvisorModel();
                $data = array_merge($commonData, [
                    'nama_layanan' => $this->request->getPost('judul'),
                    'penjelasan_layanan' => $this->request->getPost('topik'),
                    'jml_klien' => $this->request->getPost('jumlah_klien'),
                    'keterangan' => $this->request->getPost('keterangan'),
                ]);
                break;
            case 'kegiatan_lainnya':
                $model = new KegiatanLainnyaModel();
                $data = array_merge($commonData, [
                    'nama_kegiatan' => $this->request->getPost('judul'),
                    'jml_klien' => $this->request->getPost('jumlah_klien'),
                    'media' => $this->request->getPost('lokasi'),
                    'keterangan' => $this->request->getPost('keterangan'),
                    'jml_nasihat' => 0,
                ]);
                break;
            default:
                return redirect()->back()->withInput()->with('error', 'Jenis kegiatan tidak valid');
        }

        if ($model->insert($data)) {
            return redirect()->to('/laporan-kegiatan/absensi')->with('success', 'Absensi berhasil dibuat. Link: ' . $linkAbsensi);
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data absensi');
        }
    }
    public function delete($kegiatanType, $id)
    {
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

        if ($model->delete($id)) {
            // Also delete from absensi_peserta
            $absensiPesertaModel = new \App\Models\AbsensiPesertaModel();
            
            // Map the type to what's stored in absensi_peserta
            $mappedType = strtolower(str_replace(' ', '_', $kegiatanType));
            if ($mappedType == 'seminar_fgd') $mappedType = 'seminar';
            elseif ($mappedType == 'pelatihan_simulasi') $mappedType = 'pelatihan';
            
            $absensiPesertaModel->where('kegiatan_type', $mappedType)
                                ->where('kegiatan_id', $id)
                                ->delete();

            return redirect()->to('/laporan-kegiatan/absensi')->with('success', 'Data absensi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus data absensi.');
    }

    public function edit($kegiatanType, $id)
    {
        $model = null;
        $mappedType = strtolower(str_replace(' ', '_', $kegiatanType));

        switch ($mappedType) {
            case 'seminar_fgd':
            case 'seminar':
                $model = new SeminarModel();
                break;
            case 'pelatihan_simulasi':
            case 'pelatihan':
                $model = new PelatihanModel();
                break;
            case 'signal':
            case 'signals':
                $model = new SignalModel();
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
        if (!$kegiatan) {
            return redirect()->to('/laporan-kegiatan/absensi')->with('error', 'Kegiatan tidak ditemukan');
        }

        $wpaModel = new WpaModel();
        $cwpaModel = new CwpaModel();

        $data['wpa_list'] = $wpaModel->findAll();
        $data['cwpa_list'] = $cwpaModel->select('cwpa.*, users.name as user_name')->join('users', 'users.id = cwpa.user_id')->findAll();
        $templateModel = new \App\Models\WhatsappTemplateModel();
        $data['whatsapp_templates'] = $templateModel->findAll();
        $data['title'] = 'Edit Absensi';
        $data['kegiatan'] = $kegiatan;
        $data['kegiatanType'] = $mappedType;
        $data['activeMenu'] = 'absensi';

        return view('laporan-kegiatan/absensi/edit', $data);
    }

    public function update($kegiatanType, $id)
    {
        $model = null;
        $mappedType = strtolower(str_replace(' ', '_', $kegiatanType));

        switch ($mappedType) {
            case 'seminar_fgd':
            case 'seminar':
                $model = new SeminarModel();
                break;
            case 'pelatihan_simulasi':
            case 'pelatihan':
                $model = new PelatihanModel();
                break;
            case 'signal':
            case 'signals':
                $model = new SignalModel();
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
        if (!$kegiatan) {
            return redirect()->to('/laporan-kegiatan/absensi')->with('error', 'Kegiatan tidak ditemukan');
        }

        $cwpaId = $this->request->getPost('cwpa_id');
        $commonData = [
            'wpa_id' => $this->request->getPost('wpa_id'),
            'cwpa_id' => empty($cwpaId) ? null : $cwpaId,
            'produk' => $this->request->getPost('produk'),
            'tanggal' => $this->request->getPost('tanggal_kegiatan'),
            'expired_link_kode_qr' => $this->request->getPost('expired_link_kode_qr') ?: null,
            'format_notif_wa' => $this->request->getPost('format_notif_wa') ?: null,
        ];

        switch ($mappedType) {
            case 'seminar_fgd':
            case 'seminar':
                $data = array_merge($commonData, [
                    'judul' => $this->request->getPost('judul'),
                    'jml_peserta' => $this->request->getPost('jumlah_klien'),
                    'lokasi' => $this->request->getPost('lokasi'),
                    'topik' => $this->request->getPost('topik'),
                    'keterangan' => $this->request->getPost('keterangan'),
                ]);
                break;
            case 'pelatihan_simulasi':
            case 'pelatihan':
                $data = array_merge($commonData, [
                    'judul' => $this->request->getPost('judul'),
                    'jml_peserta' => $this->request->getPost('jumlah_klien'),
                    'lokasi' => $this->request->getPost('lokasi'),
                    'topik' => $this->request->getPost('topik'),
                    'keterangan' => $this->request->getPost('keterangan'),
                ]);
                break;
            case 'signal':
            case 'signals':
                $data = array_merge($commonData, [
                    'jml_peserta' => $this->request->getPost('jumlah_klien'),
                    'media' => $this->request->getPost('lokasi'),
                    'keterangan' => $this->request->getPost('keterangan'),
                ]);
                break;
            case 'konsultasi':
                $data = array_merge($commonData, [
                    'nama_klien' => 'Klien (Estimasi: '.$this->request->getPost('jumlah_klien').')',
                    'media' => $this->request->getPost('lokasi'),
                    'keterangan' => $this->request->getPost('keterangan'),
                ]);
                break;
            case 'expert_advisor':
                $data = array_merge($commonData, [
                    'nama_layanan' => $this->request->getPost('judul'),
                    'penjelasan_layanan' => $this->request->getPost('topik'),
                    'jml_klien' => $this->request->getPost('jumlah_klien'),
                    'keterangan' => $this->request->getPost('keterangan'),
                ]);
                break;
            case 'kegiatan_lainnya':
                $data = array_merge($commonData, [
                    'nama_kegiatan' => $this->request->getPost('judul'),
                    'jml_klien' => $this->request->getPost('jumlah_klien'),
                    'media' => $this->request->getPost('lokasi'),
                    'keterangan' => $this->request->getPost('keterangan'),
                ]);
                break;
            default:
                return redirect()->back()->withInput()->with('error', 'Jenis kegiatan tidak valid');
        }

        if ($model->update($id, $data)) {
            return redirect()->to('/laporan-kegiatan/absensi')->with('success', 'Data absensi berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data absensi');
        }
    }

    public function detail($kegiatanType, $id)
    {
        $model = null;
        $mappedType = strtolower(str_replace(' ', '_', $kegiatanType));
        $dbType = $mappedType;

        switch ($mappedType) {
            case 'seminar_fgd':
            case 'seminar':
                $model = new SeminarModel();
                $dbType = 'seminar_fgd';
                break;
            case 'pelatihan_simulasi':
            case 'pelatihan':
                $model = new PelatihanModel();
                $dbType = 'pelatihan_simulasi';
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
        if (!$kegiatan) {
            return redirect()->to('/laporan-kegiatan/absensi')->with('error', 'Kegiatan tidak ditemukan');
        }

        // Get Name
        if ($mappedType == 'seminar_fgd' || $mappedType == 'seminar' || $mappedType == 'pelatihan_simulasi' || $mappedType == 'pelatihan') $kegiatan['nama'] = $kegiatan['judul'];
        elseif ($mappedType == 'signal' || $mappedType == 'signals' || $mappedType == 'konsultasi') $kegiatan['nama'] = $kegiatan['keterangan'] ?? 'Kegiatan';
        elseif ($mappedType == 'expert_advisor') $kegiatan['nama'] = $kegiatan['nama_layanan'];
        elseif ($mappedType == 'kegiatan_lainnya') $kegiatan['nama'] = $kegiatan['nama_kegiatan'];

        // Get WPA and CWPA names
        $wpaModel = new \App\Models\WpaModel();
        $wpa = $wpaModel->find($kegiatan['wpa_id'] ?? 0);
        $kegiatan['wpa_name'] = $wpa['name'] ?? '-';
        
        $cwpaName = '-';
        if (!empty($kegiatan['cwpa_id'])) {
            $cwpaModel = new \App\Models\CwpaModel();
            $cwpa = $cwpaModel->select('users.name')->join('users', 'users.id = cwpa.user_id')->where('cwpa.id', $kegiatan['cwpa_id'])->first();
            $cwpaName = $cwpa['name'] ?? '-';
        }
        $kegiatan['cwpa_name'] = $cwpaName;

        $absensiPesertaModel = new \App\Models\AbsensiPesertaModel();
        
        $peserta = $absensiPesertaModel->select('absensi_peserta.*, users.name as user_name, users.email, users.phone, users.affiliator_code, referrer.name as referrer_name')
                                       ->join('users', 'users.id = absensi_peserta.user_id')
                                       ->join('users as referrer', 'referrer.code_referral = users.affiliator_code', 'left')
                                       ->whereIn('kegiatan_type', [$dbType, str_replace('_fgd', '', str_replace('_simulasi', '', $dbType))])
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

        return view('laporan-kegiatan/absensi/detail', $data);
    }

    public function exportPdf($kegiatanType, $id)
    {
        $model = null;
        $mappedType = strtolower(str_replace(' ', '_', $kegiatanType));
        $dbType = $mappedType;

        switch ($mappedType) {
            case 'seminar_fgd':
            case 'seminar':
                $model = new SeminarModel();
                $dbType = 'seminar_fgd';
                break;
            case 'pelatihan_simulasi':
            case 'pelatihan':
                $model = new PelatihanModel();
                $dbType = 'pelatihan_simulasi';
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
        if (!$kegiatan) {
            return redirect()->to('/laporan-kegiatan/absensi')->with('error', 'Kegiatan tidak ditemukan');
        }

        // Get Name
        if ($mappedType == 'seminar_fgd' || $mappedType == 'seminar' || $mappedType == 'pelatihan_simulasi' || $mappedType == 'pelatihan') $kegiatan['nama'] = $kegiatan['judul'];
        elseif ($mappedType == 'signal' || $mappedType == 'signals' || $mappedType == 'konsultasi') $kegiatan['nama'] = $kegiatan['keterangan'] ?? 'Kegiatan';
        elseif ($mappedType == 'expert_advisor') $kegiatan['nama'] = $kegiatan['nama_layanan'];
        elseif ($mappedType == 'kegiatan_lainnya') $kegiatan['nama'] = $kegiatan['nama_kegiatan'];

        // Get WPA and CWPA names
        $wpaModel = new \App\Models\WpaModel();
        $wpa = $wpaModel->find($kegiatan['wpa_id'] ?? 0);
        $kegiatan['wpa_name'] = $wpa['name'] ?? '-';
        
        $cwpaName = '-';
        if (!empty($kegiatan['cwpa_id'])) {
            $cwpaModel = new \App\Models\CwpaModel();
            $cwpa = $cwpaModel->select('users.name')->join('users', 'users.id = cwpa.user_id')->where('cwpa.id', $kegiatan['cwpa_id'])->first();
            $cwpaName = $cwpa['name'] ?? '-';
        }
        $kegiatan['cwpa_name'] = $cwpaName;

        $absensiPesertaModel = new \App\Models\AbsensiPesertaModel();
        
        $peserta = $absensiPesertaModel->select('absensi_peserta.*, users.name as user_name, users.email, users.phone, users.affiliator_code, referrer.name as referrer_name')
                                       ->join('users', 'users.id = absensi_peserta.user_id')
                                       ->join('users as referrer', 'referrer.code_referral = users.affiliator_code', 'left')
                                       ->whereIn('kegiatan_type', [$dbType, str_replace('_fgd', '', str_replace('_simulasi', '', $dbType))])
                                       ->where('kegiatan_id', $id)
                                       ->orderBy('created_at', 'DESC')
                                       ->findAll();

        $data = [
            'kegiatan' => $kegiatan,
            'kegiatanType' => ucwords(str_replace('_', ' ', $kegiatanType)),
            'peserta' => $peserta
        ];

        return view('laporan-kegiatan/absensi/export-pdf', $data);
    }

    public function deletePeserta($id)
    {
        $absensiPesertaModel = new \App\Models\AbsensiPesertaModel();
        if ($absensiPesertaModel->delete($id)) {
            return redirect()->back()->with('success', 'Peserta berhasil dihapus dari absensi.');
        }
        return redirect()->back()->with('error', 'Gagal menghapus peserta.');
    }
}
