<?php

namespace App\Controllers\Webhook;

use App\Controllers\BaseController;
use App\Models\OtpModel;
use App\Libraries\Balesotomatis;
use App\Models\UserModel;

class BalesotomatisWebhook extends BaseController
{
    public function incoming()
    {
        $logging = [];

        // 1. Verify Authentication Header (Device ID/Number ID)
        $webhookKey = env('BALESOTOMATIS_WEBHOOK_KEY');
        $headerId = $this->request->getHeaderLine('BLS-OTO-NUMBERID');

        // Log the header to help user figure out their Number ID
        log_message('info', 'DEBUG - Balesotomatis Number ID received is: ' . $headerId);

        // Cek keamanan hanya jika BALESOTOMATIS_WEBHOOK_KEY sudah diisi di .env
        if (!empty($webhookKey) && $headerId !== $webhookKey) {
            log_message('error', 'Balesotomatis Webhook Unauthorized: Invalid BLS-OTO-NUMBERID');
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $json = $this->request->getJSON(true);

        if (!$json || !isset($json['type'])) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid payload']);
        }

        if ($json['type'] === 'incoming_chat' && isset($json['data'])) {
            $data = $json['data'];

            // Extract phone number from chat_id (e.g. 62812345678@c.us)
            $chatId = $data['chat_id'] ?? '';
            $phone = explode('@', $chatId)[0];
            $cleanPhone = preg_replace('/\D+/', '', $phone) ?: '';

            $messageBody = trim($data['message_body'] ?? '');

            // Only process if message body exists and it's from user
            if (!empty($messageBody) && empty($data['is_from_me'])) {
                log_message('info', 'Balesotomatis Webhook received msg: ' . $messageBody . ' from ' . $phone);

                // Pesan operasional lama (OTP/registrasi/absensi/reset password) tetap
                // diproses oleh alur khusus. Pesan lainnya masuk ke antrean CRM.
                if (!$this->isOperationalMessage($messageBody)) {
                    try {
                        (new \App\Services\CrmService())->onMessageReceived(
                            $cleanPhone,
                            $messageBody,
                            (string) ($data['message_id'] ?? $data['id'] ?? '') ?: null
                        );
                    } catch (\Throwable $e) {
                        log_message('error', 'CRM incoming webhook failed: ' . $e->getMessage());
                    }
                }

                $otp = null;
                $registrationData = [
                    'name' => null,
                    'email' => null,
                    'affiliate_code' => null,
                ];

                // check if messageBody is forgot password text
                if ($messageBody === "Halo Tim ALMAI, saya lupa password login Almai.id. \n\nMohon diproses.\n\nTerima kasih") {
                    $this->processForgotPassword($phone);
                }

                if (
                    preg_match('/^Halo Tim ALMAI,\s*Berikut data saya:/i', $messageBody)
                    && preg_match('/Nama Lengkap:/i', $messageBody)
                    && preg_match('/Email:/i', $messageBody)
                    && preg_match('/Kode Referral:/i', $messageBody)
                ) {
                    $channel = 'whatsapp';

                    if (preg_match('/Nama Lengkap:\s*(.+)/i', $messageBody, $matches)) {
                        $registrationData['name'] = trim($matches[1]);
                    }
                    if (preg_match('/Email:\s*(.+)/i', $messageBody, $matches)) {
                        $registrationData['email'] = trim($matches[1]);
                    }
                    if (preg_match('/Kode Referral:\s*(.+)/i', $messageBody, $matches)) {
                        $registrationData['affiliate_code'] = trim($matches[1]);
                    }

                    $otpModel = new OtpModel();
                    $otp = $otpModel->generateOtp($phone, 'registration', $channel);

                    log_message('info', 'Webhook generated registration OTP for ' . $phone . ': ' . $otp);
                }

                // Process attendance only after the user sends the WhatsApp
                // confirmation. Keep recognizing the old wording for links that
                // may already have been generated before this change.
                if ($this->isAttendanceConfirmation($messageBody)) {
                    // 1. Find user by phone number
                    $userModel = new \App\Models\UserModel();
                    $suffix = substr($cleanPhone, -9);;
                    // eg:6281238888888 can be 081238888888 or +6281238888888
                    $user = $userModel->like('phone', $suffix)->first();

                    if ($user) {
                        // 2. Find the user's latest check-in
                        $absensiPesertaModel = new \App\Models\AbsensiPesertaModel();
                        $latestCheckin = $absensiPesertaModel->where('user_id', $user['id'])
                            ->orderBy('created_at', 'DESC')
                            ->first();

                        if ($latestCheckin) {
                            $kegiatanType = $latestCheckin['kegiatan_type'];
                            $kegiatanId = $latestCheckin['kegiatan_id'];

                            // Find the event
                            $models = [
                                'seminar_fgd' => new \App\Models\SeminarModel(),
                                'pelatihan_simulasi' => new \App\Models\PelatihanModel(),
                                'signals' => new \App\Models\SignalModel(),
                                'konsultasi' => new \App\Models\KonsultasiModel(),
                                'expert_advisor' => new \App\Models\ExpertAdvisorModel(),
                                'kegiatan_lainnya' => new \App\Models\KegiatanLainnyaModel(),
                            ];

                            if (isset($models[$kegiatanType])) {
                                $event = $models[$kegiatanType]->find($kegiatanId);

                                if ($event && !empty($event['format_notif_wa']) && !\App\Services\AttendanceWhatsappNotificationService::wasSent((int) $latestCheckin['id'])) {
                                    $templateId = $event['format_notif_wa'];
                                    $replyMessage = '';

                                    if (is_numeric($templateId)) {
                                        $waTemplateModel = new \App\Models\WhatsappTemplateModel();
                                        $template = $waTemplateModel->find($templateId);
                                        if ($template && !empty($template['isi_pesan'])) {
                                            $replyMessage = $template['isi_pesan'];
                                        }
                                    } else {
                                        $replyMessage = $templateId;
                                    }

                                    if (!empty($replyMessage)) {
                                        $replyMessage = \App\Services\AttendanceWhatsappNotificationService::renderMessage(
                                            $replyMessage,
                                            $event,
                                            $user,
                                            $kegiatanType
                                        );

                                        $secretKey = env('BALESOTOMATIS_SECRET_KEY');
                                        $licensesKey = env('BALESOTOMATIS_LICENSES_KEY');

                                        if ($secretKey && $licensesKey) {
                                            Balesotomatis::configure($secretKey, $licensesKey);
                                            $attendanceResult = Balesotomatis::sendPersonalMessage($phone, $replyMessage);
                                            if (!empty($attendanceResult['success'])) {
                                                \App\Services\AttendanceWhatsappNotificationService::markAsSent((int) $latestCheckin['id']);
                                                log_message('info', 'Attendance confirmation sent via webhook for check-in ID ' . $latestCheckin['id'] . '.');
                                            } else {
                                                log_message('error', 'Attendance confirmation failed via webhook for check-in ID ' . $latestCheckin['id'] . ': ' . ($attendanceResult['error'] ?? 'Unknown error'));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // Extract 6-digit OTP using regex or use generated registration OTP
                if (preg_match('/\b(\d{6})\b/', $messageBody, $matches) || $otp) {
                    $extractedOtp = $matches[1] ?? $otp;

                    // Check OTP
                    $otpModel = new OtpModel();
                    $isVerified = $otpModel->markAsVerifiedViaWa($phone, $extractedOtp);

                    $logging = [
                        '$isVerified' => $isVerified,
                        '$phone' => $phone,
                        '$extractedOtp' => $extractedOtp,
                        '$registrationData' => $registrationData,
                    ];

                    if ($isVerified) {
                        log_message('info', 'OTP ' . $extractedOtp . ' verified via Webhook for ' . $phone);

                        if ($otp) {
                            $userModel = new \App\Models\UserModel();

                            if (!empty($phone) && $userModel->where('phone', $phone)->first()) {
                                // https://almai.id/forgot-password
                                $secretKey = env('BALESOTOMATIS_SECRET_KEY');
                                $licensesKey = env('BALESOTOMATIS_LICENSES_KEY');
                                if ($secretKey && $licensesKey) {
                                    Balesotomatis::configure($secretKey, $licensesKey);
                                    $logging['responseSendPersonalMessage'] = Balesotomatis::sendPersonalMessage($phone, "❌ Nomor WA sudah terdaftar. Silahkan login:\n" . base_url('login') . " \n\n Atau jika lupa sandi silahkan klik link berikut:\n" . base_url('forgot-password'));
                                }
                                return $this->response->setJSON(['success' => false, 'message' => 'Nomor HP sudah terdaftar']);
                            }

                            $affiliateCode = $registrationData['affiliate_code'];
                            if (empty($affiliateCode)) {
                                return $this->response->setJSON(['success' => false, 'message' => 'Kode Referral wajib diisi. Silakan pilih salah satu WPA jika belum memiliki kode.']);
                            }

                            $referrer = $userModel->findByReferralCode($affiliateCode);
                            if (!$referrer) {
                                return $this->response->setJSON(['success' => false, 'message' => 'Kode Referral tidak valid atau tidak ditemukan.']);
                            }

                            $password = 'alma123';
                            $userData = [
                                'name' => $registrationData['name'] ?? '',
                                'email' => $registrationData['email'] ?? '',
                                'phone' => $phone,
                                'password' => $password,
                                'level_id' => \App\Models\LevelModel::LEVEL_USER,
                                'status' => 'active',
                                'affiliator_code' => $affiliateCode,
                                'phone_verified_at' => date('Y-m-d H:i:s'),
                                'email_verified_at' => null,
                            ];

                            $userId = $userModel->insert($userData);
                            if (!$userId) {
                                return $this->response->setJSON(['success' => false, 'message' => 'Gagal membuat akun. Silakan coba lagi.']);
                            }

                            $otpModel->where('phone', $phone)->where('used', 2)->set(['used' => 1])->update();

                            $poinService = new \App\Libraries\PoinService();
                            $poinService->processReferralRegistration($userId, $referrer['id']);

                            $user = $userModel->find($userId);
                            $poinModel = new \App\Models\PoinModel();
                            $userPoin = $poinModel->getUserBalance($user['id']);

                            if (!empty($registrationData['email'])) {
                                try {
                                    $emailService = new \App\Libraries\EmailService();
                                    $emailService->sendAccountCredentials($registrationData['email'], $registrationData['name'] ?: 'User', $password, 'ALMAI');
                                } catch (\Throwable $e) {
                                    log_message('error', 'Failed to send Reg Success Email from webhook: ' . $e->getMessage());
                                }
                            }

                            try {
                                $secretKey = env('BALESOTOMATIS_SECRET_KEY');
                                $licensesKey = env('BALESOTOMATIS_LICENSES_KEY');
                                if ($secretKey && $licensesKey) {
                                    Balesotomatis::configure($secretKey, $licensesKey);

                                    $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    $tanggal = date('d') . ' ' . $months[(int) date('m')] . ' ' . date('Y, H.i');

                                    $message = "✅ Registrasi Berhasil!\n";
                                    $message .= "Terima kasih telah bergabung di Almai.id.\n\n";
                                    $message .= "Data Registrasi\n";
                                    $message .= "👤 Nama: " . ($registrationData['name'] ?? '') . "\n";
                                    $message .= "📅 Tanggal: $tanggal WIB\n";
                                    $message .= "📱 No. HP: $phone\n";
                                    if (!empty($registrationData['email'])) {
                                        $message .= "📧 Email: " . $registrationData['email'] . "\n";
                                    }
                                    $message .= "🔑 Password: *" . $password . "*\n";
                                    if (!empty($referrer)) {
                                        $roleName = strtoupper(\App\Models\LevelModel::roleStringFromLevel((int) $referrer['level_id']));
                                        $message .= "🤝 $roleName: {$referrer['name']} \n";
                                    }
                                    $message .= "🎁 Bonus Registrasi: 100 Almai Poin\n\n";
                                    $message .= "Silakan login ke Dashboard Anda untuk mulai mengakses seluruh layanan Almai.\n";
                                    $message .= "🔗 Login Dashboard\n" . base_url('login') . "\n\n";
                                    $message .= "💎 Pelajari manfaat Almai Poin\n" . base_url('almai-poin') . "\n\n";
                                    $message .= "Selamat bergabung dan semoga sukses bersama Almai! 🚀";

                                    Balesotomatis::sendPersonalMessage($phone, $message);
                                }
                            } catch (\Throwable $e) {
                                log_message('error', 'Failed to send Reg Success WhatsApp via webhook: ' . $e->getMessage());
                            }

                            return $this->response->setJSON([
                                'success' => true,
                                'message' => 'Registrasi berhasil via webhook.',
                                'phone' => $phone,
                                'user_id' => $userId,
                            ]);
                        }

                        $secretKey = env('BALESOTOMATIS_SECRET_KEY');
                        $licensesKey = env('BALESOTOMATIS_LICENSES_KEY');
                        if ($secretKey && $licensesKey) {
                            Balesotomatis::configure($secretKey, $licensesKey);
                            $logging['responseSendPersonalMessage'] = Balesotomatis::sendPersonalMessage($phone, "✅ Kode OTP berhasil diverifikasi.\n\nSilakan kembali ke browser Anda untuk melanjutkan pendaftaran.");
                        }
                    }
                }
            }
        }

        return $this->response->setJSON(['success' => true, '$logging' => $logging]);
    }

    private function processForgotPassword($phone)
    {
        $userModel = new UserModel();
        // $phoneVariants can be 6281238888888, 081238888888 or +6281238888888
        $phoneVariants = [$phone, '0' . substr($phone, 2), '+' . $phone];

        $user = $userModel->whereIn('phone', $phoneVariants)
            ->first();

        if (!$user) {
            $secretKey = env('BALESOTOMATIS_SECRET_KEY');
            $licensesKey = env('BALESOTOMATIS_LICENSES_KEY');
            if ($secretKey && $licensesKey) {
                Balesotomatis::configure($secretKey, $licensesKey);
                Balesotomatis::sendPersonalMessage($phone, "❌ Nomor WA Anda belum terdaftar di Almai.id. Silakan daftar terlebih dahulu melalui link berikut:\n" . base_url('register') . "\n\n Nomor yang telah dicari: " . implode(', ', $phoneVariants));
            }
            return [
                'success' => false,
                'message' => 'Nomor WA tidak ditemukan di sistem.',
            ];
        }

        $resetModel = new \App\Models\PasswordResetModel();
        $token = $resetModel->createToken($user['email']);

        $resetLink = base_url('reset-password/' . $token);
        $secretKey = env('BALESOTOMATIS_SECRET_KEY');
        $licensesKey = env('BALESOTOMATIS_LICENSES_KEY');
        if ($secretKey && $licensesKey) {
            Balesotomatis::configure($secretKey, $licensesKey);
            Balesotomatis::sendPersonalMessage($phone, "✅ Permintaan reset password diterima.\n\nNomor WA: {$user['phone']}\n\nSilakan klik link berikut untuk mereset password Anda:\n{$resetLink}\n\nLink reset password akan kadaluarsa dalam 1 jam.\n\nJika Anda tidak meminta reset password, abaikan pesan ini.");
        }
    }

    private function isOperationalMessage(string $message): bool
    {
        return preg_match('/\b\d{6}\b/', $message) === 1
            || preg_match('/^Halo Tim ALMAI,\s*Berikut data saya:/i', $message) === 1
            || $this->isAttendanceConfirmation($message)
            || stripos($message, 'saya lupa password login Almai.id') !== false;
    }

    private function isAttendanceConfirmation(string $message): bool
    {
        return stripos($message, 'ingin mengonfirmasi kehadiran') !== false
            || stripos($message, 'Saya Akan Hadir') !== false;
    }
}
