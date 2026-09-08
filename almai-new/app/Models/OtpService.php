<?php

namespace App\Services;

use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    const OTP_EXPIRATION_SECONDS = 60 * 10; // 10 menit

    /**
     * @return bool|int
     *                  Jika false berarti pengiriman otp tidak dijalankan karena sudah verifikasi otp,
     *
     * Jika true berarti sudah dijalankan,
     *
     * Jika int berarti sudah hit rate limit dengan jumlah int tersebut
     */
    public static function handleOTPUser(User $user): bool|int
    {
        if ($user->otp_status) {
            return false;
        }

        $key = "otp_request_{$user->id}";

        if (RateLimiter::tooManyAttempts($key, 1)) {
            $secondsUntilAvailable = RateLimiter::availableIn($key);

            return $secondsUntilAvailable;
        }

        self::generate($user);

        RateLimiter::hit($key, self::OTP_EXPIRATION_SECONDS);

        return true;
    }

    /**
     * @param  string|int  $password  sebelumnya password juga dikirim ke WA. Tapi sekarang tidak digunakan lagi, karena password tidak lagi digenerate dari server
     * @param  string  $templateType  'registration' untuk template registrasi, 'otp_only' untuk template OTP saja
     *
     * @throws ConnectionException|RequestException
     */
    public static function generate(User $user, null|string|int $password = null, string $templateType = 'registration'): int
    {
        self::setTokensInvalid($user->phone);
        $code = rand(100000, 999999);
        $data = [
            'phone'         => $user->phone,
            'code'          => $code,
            'expired_at'    => now()->addSeconds(self::OTP_EXPIRATION_SECONDS),
        ];

        Token::create($data);

        // Kirim OTP via Custom WhatsApp Gateway Node.js
        $text = self::prepareFonnteMessage($user, $password, $code);
        self::sendCustomWaMessage($user->phone, $text);

        // IVOWABA dinonaktifkan, gunakan Fonnte sebagai provider
        // if ($templateType === 'otp_only') {
        //     $responseAPIIvowabaOTP = self::ivowabaSendTemplateMessage(
        //         $user->phone,
        //         config('services.ivowaba.otp_only_template_id'),
        //         self::ivowabaPrepareComponentsForOTPOnly($code)
        //     );
        // } else {
        //     $responseAPIIvowabaOTP = self::ivowabaSendTemplateMessage(
        //         $user->phone,
        //         config('services.ivowaba.registration_template_id'),
        //         self::ivowabaPrepareComponentsForRegistration($user)
        //     );
        // }

        return $code;
    }

    /**
     * Generate dan kirim OTP saja (tanpa info registrasi)
     * Berguna untuk reset password, verifikasi ulang, dll
     *
     * @throws ConnectionException|RequestException
     */
    public static function generateOTPOnly(User $user): int
    {
        return self::generate($user, null, 'otp_only');
    }

    /**
     * Generate dan kirim template registrasi (default)
     * Berguna untuk registrasi baru
     *
     * @throws ConnectionException|RequestException
     */
    public static function generateWithRegistration(User $user): int
    {
        return self::generate($user, null, 'registration');
    }

    public static function setTokensInvalid(string $phone): bool
    {
        Token::where('phone', $phone)->update(['status' => 'invalid']);

        return true;
    }

    public static function check(?string $code, mixed $phone): array
    {
        $token = Token::where('phone', $phone)
            ->where('code', $code)
            ->where('status', 'valid')
            ->where('expired_at', '>', now())
            ->first();

        if ($token) {
            $message = [
                'status'    => 'success',
                'message'   => __('OTP Code is valid.'),
            ];

            return $message;
        }

        $message = [
            'status'    => 'error',
            'message'   => __('OTP Code is invalid or expired.'),
        ];

        return $message;
    }

    /**
     * @see https://wabacdn.s45.in/docs/index.html#send-template-message Example request: One Time Password (OTP)
     * @deprecated Digunakan template gabungan sekarang
     */
    public static function ivowabaPrepareComponentsForOTP(string|int $code)
    {
        return [
            [
                'type'       => 'body',
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => $code,
                    ],
                ],
            ],
            [
                'type'          => 'button',
                'sub_type'      => 'url', 'index' => 0,
                'parameters'    => [
                    [
                        'type' => 'text',
                        'text' => $code,
                    ],
                ],
            ],
        ];
    }

    /**
     * Menyiapkan komponen untuk template registrasi
     * Template ID: 68ec92cbcd5436360113bd2e
     * Variables: {{1}} = Affiliator Code, {{2}} = Name, {{3}} = Phone, {{4}} = Email, {{5}} = Referral Code
     */
    public static function ivowabaPrepareComponentsForRegistration(User $user)
    {
        return [
            [
                'type'       => 'body',
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => $user->affiliator_code ?? '-', // {{1}} - Kode Affiliator
                    ],
                    [
                        'type' => 'text',
                        'text' => $user->name, // {{2}} - Nama
                    ],
                    [
                        'type' => 'text',
                        'text' => $user->phone, // {{3}} - Nomor Telepon
                    ],
                    [
                        'type' => 'text',
                        'text' => $user->email ?? '-', // {{4}} - Email
                    ],
                    [
                        'type' => 'text',
                        'text' => $user->code_referral, // {{5}} - Kode Referral
                    ],
                ],
            ],
        ];
    }

    /**
     * Menyiapkan komponen untuk template OTP saja
     * Template ID: 67640c21d1b77e3df85acabe
     * Variables: {{1}} = OTP Code (untuk body dan button)
     */
    public static function ivowabaPrepareComponentsForOTPOnly(string|int $code)
    {
        return [
            [
                'type'       => 'body',
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => $code, // {{1}} - OTP Code
                    ],
                ],
            ],
            [
                'type'          => 'button',
                'index'         => 0,
                'sub_type'      => 'url',
                'parameters'    => [
                    [
                        'type' => 'text',
                        'text' => $code, // {{1}} - OTP Code untuk button
                    ],
                ],
            ],
        ];
    }

    /**
     * @deprecated Method ini sudah tidak digunakan lagi karena info registrasi sudah digabung dalam template OTP
     * Kirim pesan info registrasi seperti yang di prepareFonnteMessage
     *
     * Jika env IVOWABA_INFO_REGISTRATION_TEMPLATE_ID berubah pastikan parameters-nya sudah sesuai.
     */
    public static function sendInfoRegistration(User $user)
    {
        // Method ini sudah tidak digunakan lagi karena info registrasi sudah digabung dalam template OTP
        // Silakan gunakan generate() method yang sudah mengirim template gabungan
        return;
        
        $responseAPIIvowabaInfoRegistration = self::ivowabaSendTemplateMessage($user->phone, config('services.ivowaba.info_registration_template_id'), [
            [
                'type'      => 'body',
                'parameters'=> [
                    [
                        'type'=> 'text',
                        'text'=> $user->name,
                    ],
                    [
                        'type'=> 'text',
                        'text'=> $user->phone,
                    ],
                    [
                        'type'=> 'text',
                        'text'=> $user->code_referral,
                    ],
                    [
                        'type'=> 'text',
                        'text'=> $user->affiliator_code ?? '-',
                    ],
                ],
            ],
        ]);

        // logger()->channel('otp')->notice('API IVOWABA successful response', ['request' => ['user' => ['user' => $user]], 'responseAPIIvowaba' => ['status' => $responseAPIIvowabaInfoRegistration->status(), 'body' => $responseAPIIvowabaInfoRegistration->json() ?? $responseAPIIvowabaInfoRegistration->body(), 'header' => $responseAPIIvowabaInfoRegistration->headers()]]);

    }

    /**
     * @see https://wabacdn.s45.in/docs/index.html#send-template-message
     *
     * @throws ConnectionException|RequestException
     */
    public static function ivowabaSendTemplateMessage(string $phone, ?string $templateID, array $components): Response
    {
        if (! app()->isProduction()) {
            Http::fake([
                'https://waba.ivosights.com/api/v1/messages/send-template-message' => Http::response(['test ivowabaSendTemplateMessage' => 'ok'], 200),
            ]);
        }

        $response = Http::acceptJson()
            ->withHeader('X-API-KEY', config('services.ivowaba.api_key'))
            ->post('https://waba.ivosights.com/api/v1/messages/send-template-message', [
                'wa_id'       => $phone,
                'template_id' => $templateID,
                'components'  => $components,
            ])
            ->throw();

        // // Contoh Response:
        // /** @var array{
        //  * "messaging_product":"whatsapp",
        //  * "contacts": array{ "input": "+6281238878561", "wa_id": "6281238878561" },
        //  * "messages": array{
        //  *     "id": "wamid.HBgNNjI4MTIzODg3ODU2MRUCABEYEkVDQzY5QkQwNTgxRUUyRThBNwA=",
        //  *     "message_status": "accepted"
        //  *     }
        //  * } $json
        //  */
        // $json = $response->json();

        return $response;
    }

    /**
     * Menyiapkan pesan OTP untuk dikirim via Fonnte.
     */
    public static function prepareFonnteMessage(User $user, $password, $code)
    {
        $text = "Registrasi! $user->affiliator_code \n";
        $text .= "\n";
        $text .= "ðŸ‘‹ðŸ¤© !\n";
        $text .= "Hi Ka: $user->name !\n";
        $text .= "\n";
        $text .= "Terima kasih telah mendaftar pada layanan Almai, \n";
        $text .= "\n";
        $text .= "====== Kredensial ====== \n";
        $text .= "Nomor Telepon: $user->phone \n";

        if ($password) {
            $text .= "Password: $password \n";
        }

        $text .= "OTP Code: $code \n";
        $text .= "==================== \n";
        $text .= 'Kamu bisa mulai dengan login untuk menggunakan layanan almai. Dengan klik link berikut '.route('login')." \n";
        $text .= "\n";
        $text .= "\n";
        $text .= "Terima kasih! \n";
        $text .= '*Almai* ðŸ‡®ðŸ‡©';

        return $text;
    }

    /**
     * Kirim pesan ke nomor WhatsApp via Custom Node.js Gateway.
     */
    public static function sendCustomWaMessage(string $phone, string $message): string
    {
        $curl = curl_init();

        $waUrl = trim(env('WAGW_URL', 'http://localhost:1337'));
        $waApiKey = trim(env('WAGW_API_KEY', ''));

        curl_setopt_array($curl, [
            CURLOPT_URL            => $waUrl . '/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode([
                'phone' => $phone,
                'message' => $message
            ]),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-api-key: ' . $waApiKey
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            logger()->channel('otp')->error('Custom WA Gateway Error.', ['error' => $err]);
            return json_encode(['success' => false, 'message' => $err]);
        }

        return $response;
    }
}
