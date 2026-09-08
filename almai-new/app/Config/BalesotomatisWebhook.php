<?php

namespace App\Controllers\Webhook;

use App\Controllers\BaseController;
use App\Models\OtpModel;
use App\Models\UserModel;
use App\Models\PendingRegistrationModel;
use App\Libraries\Balesotomatis;
use App\Libraries\PoinService;

class BalesotomatisWebhook extends BaseController
{
    public function incoming()
    {
        // 1. Verify Authentication Header (Device ID/Number ID)
        $webhookKey = env('BALESOTOMATIS_WEBHOOK_KEY');
        $headerId = $this->request->getHeaderLine('BLS-OTO-NUMBERID');
        
        log_message('info', 'DEBUG - Balesotomatis Number ID received is: ' . $headerId);
        
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
            
            $messageBody = trim($data['message_body'] ?? '');
            
            if (!empty($messageBody) && empty($data['is_from_me'])) {
                log_message('info', 'Balesotomatis Webhook received msg: ' . $messageBody . ' from ' . $phone);
                
                // === ALMAI REGISTRATION SYSTEM v2.0 TOKEN DETECTOR ===
                if (preg_match('/REG-[A-Z0-9]{8}/i', $messageBody, $matches)) {
                    $token = strtoupper($matches[0]);
                    log_message('info', "Registration Token v2.0 detected: {$token} from {$phone}");

                    $pendingModel = new PendingRegistrationModel();
                    $pendingRecord = $pendingModel->verifyTokenViaWa($token, $phone);

                    if ($pendingRecord) {
                        $userModel = new UserModel();

                        // Check if user already registered with this email or phone
                        $existingUser = $userModel->where('email', $pendingRecord['email'])
                            ->orWhere('phone', $pendingRecord['phone'])
                            ->first();

                        if (!$existingUser) {
                            $affiliateCode = $pendingRecord['affiliator_code'];

                            // Insert new user
                            $userData = [
                                'name'            => $pendingRecord['name'],
                                'email'           => $pendingRecord['email'],
                                'phone'           => $pendingRecord['phone'],
                                'password'        => $pendingRecord['password'], // Already hashed in PendingModel
                                'level_id'        => \App\Models\LevelModel::LEVEL_USER,
                                'status'          => 'active',
                                'affiliator_code' => $affiliateCode,
                                'otp_status'      => 1,
                                'phone_verified_at' => date('Y-m-d H:i:s')
                            ];

                            // Bypass double hash in UserModel by inserting raw
                            $db = \Config\Database::connect();
                            $db->table('users')->insert([
                                'name'            => $userData['name'],
                                'email'           => $userData['email'],
                                'phone'           => $userData['phone'],
                                'password'        => $userData['password'],
                                'level_id'        => $userData['level_id'],
                                'status'          => $userData['status'],
                                'affiliator_code' => $userData['affiliator_code'],
                                'otp_status'      => $userData['otp_status'],
                                'phone_verified_at' => $userData['phone_verified_at'],
                                'created_at'      => date('Y-m-d H:i:s'),
                                'updated_at'      => date('Y-m-d H:i:s')
                            ]);
                            $newUserId = $db->insertID();

                            // Update user_id in pending record
                            $pendingModel->update($pendingRecord['id'], ['user_id' => $newUserId]);

                            // Connect Referrer & Award Points
                            $affiliatorName = 'Direct ALMAI';
                            $referrer = $userModel->findByReferralCode($affiliateCode);
                            if ($referrer) {
                                $affiliatorName = $referrer['name'];
                                try {
                                    $poinService = new PoinService();
                                    $poinService->processReferralRegistration($newUserId, $referrer['id']);
                                } catch (\Throwable $e) {}
                            }

                            try {
                                $poinService = new PoinService();
                                $poinService->processRegistrationBonus($newUserId);
                            } catch (\Throwable $e) {}

                            // Construct Auto-Reply WA Message
                            $loginLink = base_url('login');
                            $successMsg = "🎉 *Selamat, Registrasi Anda Berhasil!*\n\n"
                                . "Terima kasih telah bergabung bersama ALMAI.\n\n"
                                . "────────────────────────\n\n"
                                . "*Nama Lengkap*\n{$pendingRecord['name']}\n\n"
                                . "*Email*\n{$pendingRecord['email']}\n\n"
                                . "*Nomor WhatsApp*\n{$pendingRecord['phone']}\n\n"
                                . "*Affiliator Anda*\n{$affiliatorName}\n\n"
                                . "*Bonus ALMAI Point*\n100 Poin\n\n"
                                . "────────────────────────\n\n"
                                . "Silakan login ke Dashboard ALMAI:\n"
                                . "{$loginLink}\n\n"
                                . "Selamat belajar dan semoga sukses bersama ALMAI! 🚀";

                            $secretKey = env('BALESOTOMATIS_SECRET_KEY');
                            $licensesKey = env('BALESOTOMATIS_LICENSES_KEY');
                            
                            if ($secretKey && $licensesKey) {
                                Balesotomatis::configure($secretKey, $licensesKey);
                                Balesotomatis::sendPersonalMessage($pendingRecord['phone'], $successMsg);
                                log_message('info', 'Registration v2.0 WA confirmation sent to ' . $pendingRecord['phone']);
                            }
                        }
                    }
                }
                // Check if it's the attendance checkin confirmation message
                elseif (stripos($messageBody, 'Saya Akan Hadir') !== false) {
                    // Attendance logic...
                }
                // Extract 6-digit OTP using regex (legacy fallback)
                elseif (preg_match('/\b(\d{6})\b/', $messageBody, $matches)) {
                    $extractedOtp = $matches[1];
                    $otpModel = new OtpModel();
                    $isVerified = $otpModel->markAsVerifiedViaWa($phone, $extractedOtp);
                    
                    if ($isVerified) {
                        $secretKey = env('BALESOTOMATIS_SECRET_KEY');
                        $licensesKey = env('BALESOTOMATIS_LICENSES_KEY');
                        if ($secretKey && $licensesKey) {
                            Balesotomatis::configure($secretKey, $licensesKey);
                            Balesotomatis::sendPersonalMessage($phone, "✅ Kode OTP berhasil diverifikasi.\n\nSilakan kembali ke browser Anda untuk melanjutkan pendaftaran.");
                        }
                    }
                }
            }
        }
        
        return $this->response->setJSON(['success' => true]);
    }
}
