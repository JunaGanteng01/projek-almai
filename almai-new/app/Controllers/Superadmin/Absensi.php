<?php

namespace App\Controllers\Superadmin;

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
        
        return view('superadmin/absensi/index', $data);
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

        return view('superadmin/absensi/create', $data);
    }

    public function store()
    {
        $kegiatan = $this->request->getPost('kegiatan');
        
        $kodeQr = 'ABS-' . strtoupper(uniqid());
        $linkAbsensi = base_url('absensi/checkin/' . $kodeQr);

        $cwpaId = $this->request->getPost('cwpa_id');
        $bannerPath = null;
        $bannerFile = $this->request->getFile('banner_image');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            $ext = $bannerFile->getExtension();
            $allowedExts = ['jpg', 'jpeg', 'png'];
            if (!in_array(strtolower($ext), $allowedExts)) {
                return redirect()->back()->withInput()->with('error', 'Format gambar harus JPG, JPEG, atau PNG.');
            }
            if ($bannerFile->getSizeByUnit('mb') > 10) {
                return redirect()->back()->withInput()->with('error', 'Ukuran gambar maksimal 10 MB.');
            }

            $newName = $bannerFile->getRandomName();
            $bannerFile->move(FCPATH . 'uploads/banners', $newName);
            $bannerPath = 'uploads/banners/' . $newName;
        }

        $commonData = [
            'wpa_id' => $this->request->getPost('wpa_id'),
            'cwpa_id' => empty($cwpaId) ? null : $cwpaId,
            'produk' => $this->request->getPost('produk'),
            'tanggal' => $this->request->getPost('tanggal_kegiatan'),
            'kode_qr' => $kodeQr,
            'link_absensi' => $linkAbsensi,
            'expired_link_kode_qr' => $this->request->getPost('expired_link_kode_qr'),
            'format_notif_wa' => $this->request->getPost('format_notif_wa') ?: null,
            'registration_roles' => \App\Services\AttendanceAccessService::rolesFromAudience(
                $this->request->getPost('registration_audience')
            ),
            'created_by_admin_id' => session()->get('userId'),
            'banner_image' => $bannerPath,
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

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $attendanceId = $model->insert($data, true);

            if ($attendanceId) {
                $syncData = array_merge($data, [
                    'event_title' => $this->request->getPost('judul'),
                    'event_time' => $this->request->getPost('jam'),
                    'event_category' => $this->request->getPost('kategori') ?: null,
                    'event_quota' => $this->request->getPost('jumlah_klien'),
                ]);
                (new \App\Services\AttendanceEventSyncService())->sync($kegiatan, (int) $attendanceId, $syncData);
            }
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Sinkronisasi Event Gratis gagal saat Superadmin membuat absensi: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan absensi dan Event Gratis.');
        }

        $db->transComplete();

        if ($attendanceId && $db->transStatus()) {
            return redirect()->to('/superadmin/absensi')->with('success', 'Absensi berhasil dibuat. Link: ' . $linkAbsensi);
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data absensi');
        }
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
            return redirect()->to('/superadmin/absensi')->with('error', 'Kegiatan tidak ditemukan');
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

        return view('superadmin/absensi/edit', $data);
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
            return redirect()->to('/superadmin/absensi')->with('error', 'Kegiatan tidak ditemukan');
        }

        $cwpaId = $this->request->getPost('cwpa_id');
        $bannerPath = null;
        $bannerFile = $this->request->getFile('banner_image');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            $ext = $bannerFile->getExtension();
            $allowedExts = ['jpg', 'jpeg', 'png'];
            if (!in_array(strtolower($ext), $allowedExts)) {
                return redirect()->back()->withInput()->with('error', 'Format gambar harus JPG, JPEG, atau PNG.');
            }
            if ($bannerFile->getSizeByUnit('mb') > 10) {
                return redirect()->back()->withInput()->with('error', 'Ukuran gambar maksimal 10 MB.');
            }

            $newName = $bannerFile->getRandomName();
            $bannerFile->move(FCPATH . 'uploads/banners', $newName);
            $bannerPath = 'uploads/banners/' . $newName;
        }

        $commonData = [
            'wpa_id' => $this->request->getPost('wpa_id'),
            'cwpa_id' => empty($cwpaId) ? null : $cwpaId,
            'produk' => $this->request->getPost('produk'),
            'tanggal' => $this->request->getPost('tanggal_kegiatan'),
            'expired_link_kode_qr' => $this->request->getPost('expired_link_kode_qr') ?: null,
            'format_notif_wa' => $this->request->getPost('format_notif_wa') ?: null,
            'registration_roles' => \App\Services\AttendanceAccessService::rolesFromAudience(
                $this->request->getPost('registration_audience')
            ),
        ];

        if ($bannerPath) {
            $commonData['banner_image'] = $bannerPath;
        }

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

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $updated = $model->update($id, $data);

            if ($updated) {
                $syncData = array_merge($kegiatan, $data, [
                    'event_title' => $this->request->getPost('judul'),
                    'event_time' => $this->request->getPost('jam'),
                    'event_category' => $this->request->getPost('kategori') ?: null,
                    'event_quota' => $this->request->getPost('jumlah_klien'),
                ]);
                (new \App\Services\AttendanceEventSyncService())->sync($mappedType, (int) $id, $syncData);
            }
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Sinkronisasi Event Gratis gagal saat Superadmin memperbarui absensi: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui absensi dan Event Gratis.');
        }

        $db->transComplete();

        if ($updated && $db->transStatus()) {
            return redirect()->to('/superadmin/absensi')->with('success', 'Data absensi berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data absensi');
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

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $deleted = $model->delete($id);

            if ($deleted) {
                (new \App\Services\AttendanceEventSyncService())->delete($kegiatanType, (int) $id);

                // Also delete from absensi_peserta
                $absensiPesertaModel = new \App\Models\AbsensiPesertaModel();
                $mappedType = strtolower(str_replace(' ', '_', $kegiatanType));

                $absensiPesertaModel->whereIn('kegiatan_type', [$mappedType, str_replace('_fgd', '', str_replace('_simulasi', '', $mappedType))])
                                    ->where('kegiatan_id', $id)
                                    ->delete();
            }
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Sinkronisasi Event Gratis gagal saat Superadmin menghapus absensi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus absensi dan Event Gratis.');
        }

        $db->transComplete();

        if ($deleted && $db->transStatus()) {
            return redirect()->to('/superadmin/absensi')->with('success', 'Data absensi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus data absensi.');
    }

    /**
     * Check if a kegiatan has any peserta checked in.
     */
    private function hasAbsensiPeserta($kegiatanType, $kegiatanId)
    {
        $absensiPesertaModel = new \App\Models\AbsensiPesertaModel();
        $mappedType = strtolower(str_replace(' ', '_', $kegiatanType));

        return $absensiPesertaModel->whereIn('kegiatan_type', [$mappedType, str_replace('_fgd', '', str_replace('_simulasi', '', $mappedType))])
                                   ->where('kegiatan_id', $kegiatanId)
                                   ->countAllResults() > 0;
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
            return redirect()->to('/superadmin/absensi')->with('error', 'Kegiatan tidak ditemukan');
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
            'peserta' => $peserta
        ];

        return view('superadmin/absensi/detail', $data);
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
            return redirect()->to('/superadmin/absensi')->with('error', 'Kegiatan tidak ditemukan');
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

        return view('superadmin/absensi/export-pdf', $data);
    }

    public function exportCsv($kegiatanType, $id)
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
            return redirect()->to('/superadmin/absensi')->with('error', 'Kegiatan tidak ditemukan');
        }

        if ($mappedType == 'seminar_fgd' || $mappedType == 'seminar' || $mappedType == 'pelatihan_simulasi' || $mappedType == 'pelatihan') $kegiatan['nama'] = $kegiatan['judul'];
        elseif ($mappedType == 'signal' || $mappedType == 'signals' || $mappedType == 'konsultasi') $kegiatan['nama'] = $kegiatan['keterangan'] ?? 'Kegiatan';
        elseif ($mappedType == 'expert_advisor') $kegiatan['nama'] = $kegiatan['nama_layanan'];
        elseif ($mappedType == 'kegiatan_lainnya') $kegiatan['nama'] = $kegiatan['nama_kegiatan'];

        $absensiPesertaModel = new \App\Models\AbsensiPesertaModel();
        
        $peserta = $absensiPesertaModel->select('users.name as user_name, users.phone')
                                       ->join('users', 'users.id = absensi_peserta.user_id')
                                       ->whereIn('kegiatan_type', [$dbType, str_replace('_fgd', '', str_replace('_simulasi', '', $dbType))])
                                       ->where('kegiatan_id', $id)
                                       ->orderBy('absensi_peserta.created_at', 'DESC')
                                       ->findAll();

        $filename = 'Export_Absensi_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $kegiatan['nama']) . '_' . date('Ymd_His') . '.csv';

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen('php://output', 'w');
        
        // UTF-8 BOM for Excel to display characters properly
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Nama', 'No WhatsApp']);

        foreach ($peserta as $p) {
            $phone = trim($p['phone'] ?? '');
            
            // Format phone number to start with +628
            $phone = preg_replace('/[^0-9+]/', '', $phone); // Remove spaces, dashes, etc.
            if (substr($phone, 0, 1) === '0') {
                $phone = '+62' . substr($phone, 1);
            } elseif (substr($phone, 0, 2) === '62') {
                $phone = '+' . $phone;
            } elseif (substr($phone, 0, 3) !== '+62') {
                if (substr($phone, 0, 1) === '8') {
                    $phone = '+62' . $phone;
                }
            }

            fputcsv($output, [$p['user_name'], $phone]);
        }
        
        fclose($output);
        exit;
    }

    public function exportXlsx($kegiatanType, $id)
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
            return redirect()->to('/superadmin/absensi')->with('error', 'Kegiatan tidak ditemukan');
        }

        if ($mappedType == 'seminar_fgd' || $mappedType == 'seminar' || $mappedType == 'pelatihan_simulasi' || $mappedType == 'pelatihan') $kegiatan['nama'] = $kegiatan['judul'];
        elseif ($mappedType == 'signal' || $mappedType == 'signals' || $mappedType == 'konsultasi') $kegiatan['nama'] = $kegiatan['keterangan'] ?? 'Kegiatan';
        elseif ($mappedType == 'expert_advisor') $kegiatan['nama'] = $kegiatan['nama_layanan'];
        elseif ($mappedType == 'kegiatan_lainnya') $kegiatan['nama'] = $kegiatan['nama_kegiatan'];

        $absensiPesertaModel = new \App\Models\AbsensiPesertaModel();
        
        $peserta = $absensiPesertaModel->select('users.name as user_name, users.phone')
                                       ->join('users', 'users.id = absensi_peserta.user_id')
                                       ->whereIn('kegiatan_type', [$dbType, str_replace('_fgd', '', str_replace('_simulasi', '', $dbType))])
                                       ->where('kegiatan_id', $id)
                                       ->orderBy('absensi_peserta.created_at', 'DESC')
                                       ->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Fullname');
        $sheet->setCellValue('B1', 'WhatsApp');
        
        $row = 2;
        foreach ($peserta as $p) {
            $phone = trim($p['phone'] ?? '');
            
            // Format phone number to start with 628
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (substr($phone, 0, 1) === '0') {
                $phone = '62' . substr($phone, 1);
            } elseif (substr($phone, 0, 1) === '8') {
                $phone = '62' . $phone;
            }

            $sheet->setCellValue('A' . $row, $p['user_name']);
            $sheet->setCellValueExplicit('B' . $row, $phone, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $row++;
        }

        $filename = 'Export_Absensi_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $kegiatan['nama']) . '_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
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
