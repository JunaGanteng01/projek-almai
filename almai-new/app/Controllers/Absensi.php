<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SeminarModel;
use App\Models\PelatihanModel;
use App\Models\SignalModel;
use App\Models\KonsultasiModel;
use App\Models\ExpertAdvisorModel;
use App\Models\KegiatanLainnyaModel;
use App\Models\AbsensiPesertaModel;
use App\Models\PoinModel;

class Absensi extends BaseController
{
    public function checkin($kodeQr)
    {
        $userId = session()->get('userId');

        // Find the event by QR code
        $models = [
            'seminar_fgd' => new SeminarModel(),
            'pelatihan_simulasi' => new PelatihanModel(),
            'signals' => new SignalModel(),
            'konsultasi' => new KonsultasiModel(),
            'expert_advisor' => new ExpertAdvisorModel(),
            'kegiatan_lainnya' => new KegiatanLainnyaModel(),
        ];

        $event = null;
        $kegiatanType = '';
        foreach ($models as $type => $model) {
            $found = $model->where('kode_qr', $kodeQr)->first();
            if ($found) {
                $event = $found;
                $kegiatanType = $type;
                break;
            }
        }

        if (!$event) {
            return view('pages/absensi/error', [
                'title' => 'Absensi Tidak Ditemukan',
                'message' => 'Kode QR atau link absensi tidak valid.'
            ]);
        }

        // Check if expired
        if (!empty($event['expired_link_kode_qr']) && strtotime($event['expired_link_kode_qr']) < time()) {
            return view('pages/absensi/error', [
                'title' => 'Absensi Kadaluarsa',
                'message' => 'Mohon maaf, link absensi ini sudah kadaluarsa.'
            ]);
        }

        $absensiPesertaModel = new AbsensiPesertaModel();
        $registrationRestricted = \App\Services\AttendanceAccessService::isRestricted($event);
        $resolvedLevelId = session()->get('isLoggedIn')
            ? \App\Models\LevelModel::resolveLevelId(session())
            : null;
        $canCheckIn = \App\Services\AttendanceAccessService::canCheckIn($event, $resolvedLevelId);

        // If post request, process checkin
        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            if (!session()->get('isLoggedIn')) {
                return redirect()->to('/login?redirect=absensi/checkin/' . $kodeQr)->with('error', 'Silakan login terlebih dahulu untuk melakukan absensi.');
            }

            if (!$canCheckIn) {
                return redirect()->back()->with('error', 'Check-in kegiatan ini hanya tersedia untuk CWPA dan WPA.');
            }
            
            // Determine redirect paths based on level_id
            $dashboardPath = \App\Models\LevelModel::getDashboardPath(\App\Models\LevelModel::resolveLevelId(session()));
            $dashboardAbsenPath = $dashboardPath . '/absen';
            
            if ($absensiPesertaModel->isAlreadyCheckedIn($userId, $kegiatanType, $event['id'])) {
                return redirect()->to($dashboardAbsenPath)->with('error', 'Anda sudah melakukan absensi untuk kegiatan ini.');
            }

            // Insert into absensi_peserta
            $absensiId = $absensiPesertaModel->insert([
                'user_id' => $userId,
                'kegiatan_type' => $kegiatanType,
                'kegiatan_id' => $event['id'],
                'poin_awarded' => 100
            ]);

            // Give 100 points
            if ($absensiId) {
                $poinModel = new PoinModel();
                $poinModel->insert([
                    'user_id' => $userId,
                    'type' => 'absensi_reward',
                    'point' => 100,
                    'description' => 'Reward absensi acara',
                    'pointable_type' => 'absensi_peserta',
                    'pointable_id' => $absensiId,
                ]);

                // Cek nomor admin dari .env
                $adminWaNumber = env('NO_OTP', '6285128057800'); // Ganti default jika perlu

                // Open WhatsApp when an attendance notification template is set.
                // The user sends this confirmation first; Balesotomatis replies
                // only after its webhook receives the message.
                if (!empty($event['format_notif_wa'])) {
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->find($userId);
                    
                    if ($user && !empty($user['phone'])) {
                        // Generate event name dynamically based on type
                        $eventNameText = '';
                        switch ($kegiatanType) {
                            case 'seminar_fgd':
                            case 'pelatihan_simulasi':
                                $eventNameText = $event['judul'];
                                break;
                            case 'signals':
                            case 'konsultasi':
                            case 'kegiatan_lainnya':
                                $eventNameText = $event['keterangan'] ?? 'Kegiatan Almai';
                                if ($kegiatanType == 'kegiatan_lainnya') {
                                    $eventNameText = $event['nama_kegiatan'];
                                }
                                break;
                            case 'expert_advisor':
                                $eventNameText = $event['nama_layanan'];
                                break;
                        }
                        
                        // Format Tanggal: "Rabu, 5 Agustus 2026"
                        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');
                        $timestamp = strtotime($event['tanggal'] ?? $event['created_at']);
                        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][date('w', $timestamp)];
                        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][date('n', $timestamp) - 1];
                        $tanggalText = $hari . ', ' . date('j', $timestamp) . ' ' . $bulan . ' ' . date('Y', $timestamp);
                        
                        $triggerMessage = "Halo Tim ALMAI.\n\n"
                            . "Saya {$user['name']} ingin mengonfirmasi kehadiran pada acara {$eventNameText} yang diselenggarakan pada {$tanggalText}.\n\n"
                            . "Mohon bantuannya untuk mencatat konfirmasi kehadiran saya.\n\n"
                            . "Terima kasih.";
                        
                        $waUrl = "https://wa.me/{$adminWaNumber}?text=" . urlencode($triggerMessage);

                        // Redirect ke wa.me dengan pesan pemicu
                        return redirect()->to($waUrl);
                    }
                }

                return redirect()->to($dashboardAbsenPath)->with('success', 'Absensi berhasil! Anda mendapatkan 100 Poin.');
            } else {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan absensi.');
            }
        }

        // Determine title for view
        $eventName = '';
        switch ($kegiatanType) {
            case 'seminar_fgd':
            case 'pelatihan_simulasi':
                $eventName = $event['judul'];
                break;
            case 'signals':
            case 'konsultasi':
            case 'kegiatan_lainnya':
                $eventName = $event['keterangan'] ?? 'Kegiatan Almai';
                if ($kegiatanType == 'kegiatan_lainnya') {
                    $eventName = $event['nama_kegiatan'];
                }
                break;
            case 'expert_advisor':
                $eventName = $event['nama_layanan'];
                break;
        }

        $reff = $this->request->getGet('reff');
        $referrerName = null;
        if (!empty($reff)) {
            $userModel = new \App\Models\UserModel();
            $referrer = $userModel->where('code_referral', $reff)->first();
            if ($referrer) {
                $referrerName = $referrer['name'];
            }
        }

        // Format meta data matching Event::detail for social sharing
        $metaTitle = $eventName . ' - Event | Almai';
        $metaDescription = !empty($event['keterangan']) 
            ? trim(strip_tags($event['keterangan'])) 
            : 'Ikuti Event & Webinar edukasi trading oleh Wakil Penasihat Berjangka (WPA) untuk memahami strategi, risiko, dan regulasi secara tepat.';
        if (mb_strlen($metaDescription) > 160) {
            $metaDescription = mb_substr($metaDescription, 0, 157) . '...';
        }

        $metaImage = !empty($event['banner_image'])
            ? (strpos($event['banner_image'], 'http') === 0 ? $event['banner_image'] : base_url(ltrim($event['banner_image'], '/\\')))
            : base_url('images/logo.png?v=3');

        $data = [
            'title' => $eventName . ' - Almai Event',
            'meta_title' => $metaTitle,
            'meta_image' => $metaImage,
            'meta_description' => $metaDescription,
            'event' => $event,
            'eventName' => $eventName,
            'kegiatanType' => $kegiatanType,
            'kodeQr' => $kodeQr,
            'reff' => $reff,
            'referrerName' => $referrerName,
            'registrationRestricted' => $registrationRestricted,
            'canCheckIn' => $canCheckIn,
        ];

        return view('pages/absensi/checkin', $data);
    }
}
