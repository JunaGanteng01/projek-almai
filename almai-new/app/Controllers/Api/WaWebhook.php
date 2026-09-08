<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\OtpModel;
use App\Models\UserModel;
use App\Libraries\PoinService;

class WaWebhook extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        // === SECURITY: Verifikasi x-api-key ===
        $waApiKey = trim(env('WAGW_API_KEY', ''));
        $headerApiKey = $this->request->getHeaderLine('x-api-key');

        if (empty($waApiKey) || $headerApiKey !== $waApiKey) {
            log_message('error', 'WaWebhook: Unauthorized access attempt. Invalid or missing x-api-key.');
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $jsonString = $this->request->getBody();
        log_message('info', 'WaWebhook Raw Payload: ' . $jsonString);
        
        $json = json_decode($jsonString, true);
        if (!$json) {
            log_message('error', 'WaWebhook: Invalid JSON payload');
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid JSON']);
        }

        $from = $json['from'] ?? '';
        $realNumber = $json['realNumber'] ?? '';
        $body = $json['body'] ?? '';

        // Extract phone
        if (!empty($realNumber)) {
            $phone = preg_replace('/[^0-9]/', '', $realNumber);
        } else {
            $phone = preg_replace('/[^0-9]/', '', $from);
        }

        // Extract OTP
        if (preg_match('/OTP:\s*(\d{6})/', $body, $matches)) {
            $otp = $matches[1];
            
            log_message('info', "Received OTP Webhook: Phone=$phone, From=$from, OTP=$otp");

            $otpModel = new OtpModel();
            

            $otpRecord = $otpModel->where('otp', $otp)
                                  ->whereIn('type', ['registration', 'checkout'])
                                  ->where('used', 0)
                                  ->first();

            if ($otpRecord && !empty($otpRecord['phone'])) {
                $originalPhone = $otpRecord['phone'];
                
                // Normalisasi nomor telepon ke format 62... untuk perbandingan
                $phoneClean = preg_replace('/[^0-9]/', '', $phone);
                $originalPhoneClean = preg_replace('/[^0-9]/', '', $originalPhone);
                
                if (str_starts_with($phoneClean, '0')) {
                    $phoneClean = '62' . substr($phoneClean, 1);
                }
                if (str_starts_with($originalPhoneClean, '0')) {
                    $originalPhoneClean = '62' . substr($originalPhoneClean, 1);
                }

                // Validasi: Pastikan nomor pengirim SAMA dengan nomor yang didaftarkan
                if ($phoneClean !== $originalPhoneClean) {
                    log_message('error', "Phone mismatch: sender $phoneClean != registered $originalPhoneClean (From: $from)");
                    $this->sendFailedWa($phoneClean, "❌ Verifikasi gagal.\n\nNomor WhatsApp yang Anda gunakan untuk mengirim OTP tidak sama dengan nomor yang didaftarkan. Silakan gunakan nomor yang benar.");
                    return $this->respond(['success' => true]);
                }

                if ($otpRecord['type'] === 'registration') {
                    $cacheKey = 'reg_' . preg_replace('/[^0-9]/', '', $originalPhone);
                    $regData = cache()->get($cacheKey);

                    if ($regData) {
                        // Verify OTP with original phone
                        if ($otpModel->verifyOtp($originalPhone, $otp, 'registration')) {
                            
                            // Proceed with registration
                            $userModel = new UserModel();
                            
                            // Double check if already registered
                            if (!$userModel->where('phone', $originalPhone)->first()) {
                                
                                $referrerId = null;
                                $affiliatorName = '-';
                                $affiliatorRole = '-';
                                if (!empty($regData['affiliate_code'])) {
                                    $referrer = $userModel->findByReferralCode($regData['affiliate_code']);
                                    if ($referrer) {
                                        $referrerId = $referrer['id'];
                                        $affiliatorName = $referrer['name'];
                                        if (isset($referrer['level_id'])) {
                                            if ($referrer['level_id'] == \App\Models\LevelModel::LEVEL_WPA) $affiliatorRole = 'WPA';
                                            elseif ($referrer['level_id'] == \App\Models\LevelModel::LEVEL_CWPA) $affiliatorRole = 'CWPA';
                                            else $affiliatorRole = 'User';
                                        }
                                    }
                                }

                                // Auto generate password if empty
                                $userPassword = $regData['password'];
                                if (empty($userPassword)) {
                                    $randomHash = strtoupper(substr(hash('sha256', uniqid(rand(), true)), 0, 4));
                                    $userPassword = 'ALMA-' . $randomHash;
                                }

                                $userId = $userModel->insert([
                                    'name' => $regData['name'],
                                    'email' => $regData['email'] ?? null,
                                    'phone' => $originalPhone,
                                    'password' => $userPassword,
                                    'level_id' => \App\Models\LevelModel::LEVEL_USER,
                                    'status' => 'active',
                                    'affiliator_code' => $regData['affiliate_code'],
                                ]);

                                if ($userId) {
                                    // Process referral bonus
                                    if ($referrerId) {
                                        $poinService = new PoinService();
                                        $poinService->processReferralRegistration($userId, $referrerId);
                                    }
                                    
                                    // Send Success WA
                                    $dateNow = date('d M Y H:i');
                                    $loginLink = base_url('login');
                                    $bonusRegister = 100;
                                    $message = "✅ Registrasi Berhasil!\n\n"
                                        . "Tanggal   : {$dateNow}\n"
                                        . "Nama      : {$regData['name']}\n"
                                        . "No. HP    : {$originalPhone}\n"
                                        . "Password  : {$userPassword}\n"
                                        . "{$affiliatorRole} : {$affiliatorName}\n"
                                        . "Bonus Registrasi : {$bonusRegister} poin\n\n"
                                        . "Silahkan login ke dashboard Anda, untuk melanjutkan kelayanan berikutnya.\n\n"
                                        . "Login : {$loginLink}\n\n"
                                        . "Selamat bergabung di Almai | Platform Resmi Penasihat Perdagangan Derivatif & Aset Keuangan Digital 🇮🇩";

                                    $waUrl = trim(env('WAGW_URL', 'http://localhost:1337'));
                                    $waApiKey = trim(env('WAGW_API_KEY', ''));
                                    
                                    $curl = curl_init();
                                    curl_setopt_array($curl, [
                                        CURLOPT_URL => $waUrl . '/send',
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 30,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => json_encode([
                                            'phone' => $from, // SELALU gunakan $from dari webhook asli
                                            'message' => $message
                                        ]),
                                        CURLOPT_HTTPHEADER => [
                                            'Content-Type: application/json',
                                            'x-api-key: ' . $waApiKey
                                        ],
                                    ]);
                                    $response = curl_exec($curl);
                                    $err = curl_error($curl);
                                    curl_close($curl);
                                    
                                    if ($err) {
                                        log_message('error', "WaWebhook registration success send error: $err");
                                    } else {
                                        log_message('info', "WaWebhook registration success send response: " . print_r($response, true));
                                        
                                        // Check if fonnte returned status false
                                        $resObj = json_decode($response, true);
                                        if (isset($resObj['status']) && $resObj['status'] === false) {
                                            log_message('error', "Fonnte rejected for registration. Fallback...");
                                            $this->sendFailedWa($from, $message); 
                                        }
                                    }
                                    
                                    // Set verified flag in cache for frontend polling
                                    cache()->save('wa_verified_' . preg_replace('/[^0-9]/', '', $originalPhone), $userId, 3600);
                                    
                                    // Delete registration data from cache
                                    cache()->delete($cacheKey);
                                }
                            } else {
                                $this->sendFailedWa($from);
                            }
                        } else {
                            log_message('error', "Invalid OTP for $originalPhone: $otp");
                            $this->sendFailedWa($from);
                        }
                    } else {
                        log_message('error', "No registration data found for $originalPhone");
                        $this->sendFailedWa($from);
                    }
                } elseif ($otpRecord['type'] === 'checkout') {
                    if ($otpModel->verifyOtp($originalPhone, $otp, 'checkout')) {
                        $userModel = new UserModel();
                        $user = $userModel->where('phone', $originalPhone)->first();
                        $userId = $user ? $user['id'] : true;
                        
                        // Set verified flag in cache for frontend polling
                        cache()->save('wa_verified_' . preg_replace('/[^0-9]/', '', $originalPhone), $userId, 3600);
                        
                        // Send Success WA for checkout
                        $dateNow = date('d M Y H:i');
                        $message = "✅ Verifikasi Checkout Berhasil!\n\n"
                            . "Tanggal   : {$dateNow}\n"
                            . "No. HP    : {$originalPhone}\n\n"
                            . "Silakan kembali ke halaman browser Anda untuk melanjutkan proses pembayaran.";

                        $waUrl = trim(env('WAGW_URL', 'http://localhost:1337'));
                        $waApiKey = trim(env('WAGW_API_KEY', ''));
                        
                        $curl = curl_init();
                        curl_setopt_array($curl, [
                            CURLOPT_URL => $waUrl . '/send',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode([
                                'phone' => $originalPhoneClean, // Gunakan original phone yang divalidasi dan dinormalisasi (628...)
                                'message' => $message
                            ]),
                            CURLOPT_HTTPHEADER => [
                                'Content-Type: application/json',
                                'x-api-key: ' . $waApiKey
                            ],
                        ]);
                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        curl_close($curl);
                        
                        if ($err) {
                            log_message('error', "WaWebhook checkout success send error: $err");
                        } else {
                            log_message('info', "WaWebhook checkout success send response: " . print_r($response, true));
                            
                            // Check if fonnte returned status false
                            $resObj = json_decode($response, true);
                            if (isset($resObj['status']) && $resObj['status'] === false) {
                                log_message('error', "Fonnte rejected originalPhone for checkout: $originalPhone. Trying sender phone: $phone");
                                $this->sendFailedWa($phone, $message); // Send the success message to the sender phone as fallback
                            }
                        }
                    } else {
                        log_message('error', "Invalid OTP for checkout $originalPhone: $otp");
                        $this->sendFailedWa($originalPhone);
                    }
                }
            } else {
                log_message('error', "OTP Record not found or used for OTP: $otp");
                // Fallback to send failed message using the extracted phone if OTP record is missing
                $this->sendFailedWa($phone);
            }
        }

        return $this->respond(['success' => true]);
    }

    private function sendFailedWa($to, $customMessage = null)
    {
        $message = $customMessage ?: "❌ Verifikasi gagal.\n\nPenyebab:\n• OTP tidak valid atau telah kedaluwarsa.\n• Nomor WhatsApp belum terdaftar.\n\nSilakan periksa kembali data Anda dan coba lagi.";

        $waUrl = trim(env('WAGW_URL', 'http://localhost:1337'));
        $waApiKey = trim(env('WAGW_API_KEY', ''));
        
        $payload = json_encode([
            'phone' => $to,
            'message' => $message
        ]);
        
        log_message('info', "sendFailedWa Payload: " . $payload);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $waUrl . '/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-api-key: ' . $waApiKey
            ],
        ]);
        $res = curl_exec($curl);
        if ($res === false) {
            log_message('error', 'cURL Error in sendFailedWa: ' . curl_error($curl));
        } else {
            log_message('info', 'sendFailedWa Response: ' . $res);
        }
        curl_close($curl);
    }
}
