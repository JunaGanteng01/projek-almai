<?php

namespace App\Libraries;

use Config\Email as EmailConfig;

class EmailService
{
    protected $email;
    protected $config;

    public function __construct()
    {
        $this->config = new EmailConfig();
        $this->email = \Config\Services::email();
        $this->email->initialize($this->config);
    }

    /**
     * Send password reset email
     */
    public function sendPasswordReset(string $toEmail, string $userName, string $resetLink): array
    {
        $subject = 'Reset Password - ALMAI ID';

        $message = $this->getPasswordResetTemplate($userName, $resetLink);

        return $this->send($toEmail, $subject, $message);
    }

    /**
     * Send OTP email for registration
     */
    public function sendOtp(string $toEmail, string $userName, string $otp): array
    {
        $subject = 'Kode Verifikasi - ALMAI ID';

        $message = $this->getOtpTemplate($userName, $otp);

        return $this->send($toEmail, $subject, $message);
    }

    /**
     * Send welcome email after registration
     */
    public function sendWelcome(string $toEmail, string $userName): array
    {
        $subject = 'Selamat Datang di ALMAI ID!';

        $message = $this->getWelcomeTemplate($userName);

        return $this->send($toEmail, $subject, $message);
    }

    /**
     * Send transaction notification
     */
    public function sendTransactionNotification(string $toEmail, string $userName, array $transaction): array
    {
        $subject = 'Konfirmasi Transaksi - ' . $transaction['invoice_number'];

        $message = $this->getTransactionTemplate($userName, $transaction);

        return $this->send($toEmail, $subject, $message);
    }

    /**
     * Send account credentials email (for admin-created users)
     */
    public function sendAccountCredentials(string $toEmail, string $userName, string $password, string $programName = 'Scalpinghack'): array
    {
        $subject = 'Informasi Akun Anda - ALMAI ID';

        $message = $this->getAccountCredentialsTemplate($userName, $toEmail, $password, $programName);

        return $this->send($toEmail, $subject, $message);
    }

    /**
     * Send guest credentials email
     */
    public function sendGuestCredentials(string $toEmail, string $userName, ?string $password = null): array
    {
        $subject = 'Informasi Akun Anda - ALMAI ID';

        $message = $this->getGuestCredentialsTemplate($userName, $toEmail, $password);

        return $this->send($toEmail, $subject, $message);
    }

    /**
     * Core send method - dengan timeout optimization
     */
    protected function send(string $to, string $subject, string $message): array
    {
        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($to);
            $this->email->setBCC($this->config->fromEmail); // BCC Admin
            $this->email->setSubject($subject);
            $this->email->setMessage($message);

            // Set timeout untuk SMTP connection (default 5 detik)
            // Ini akan membuat email lebih cepat jika server SMTP lambat
            ini_set('default_socket_timeout', 5);

            if ($this->email->send()) {
                return ['success' => true, 'message' => 'Email berhasil dikirim'];
            } else {
                $error = $this->email->printDebugger(['headers', 'subject', 'body']);
                log_message('error', 'Email send failed: ' . $error);
                return ['success' => false, 'message' => 'Gagal mengirim email', 'error' => $error];
            }
        } catch (\Exception $e) {
            log_message('error', 'Email exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Password reset email template
     */
    protected function getPasswordResetTemplate(string $userName, string $resetLink): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #050505;">
            <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" style="height: 50px;">
                    <h1 style="color: #ffffff; margin: 10px 0 0 0; font-size: 24px;">ALMAI</h1>
                </div>
                
                <!-- Content Card -->
                <div style="background-color: #111111; border-radius: 16px; padding: 40px; border: 1px solid rgba(255,255,255,0.1);">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <div style="width: 60px; height: 60px; background-color: rgba(51,232,24,0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                            <span style="font-size: 24px;">🔐</span>
                        </div>
                    </div>
                    
                    <h2 style="color: #ffffff; text-align: center; margin: 0 0 20px 0;">Reset Password</h2>
                    
                    <p style="color: #9ca3af; line-height: 1.6; margin: 0 0 20px 0;">
                        Halo <strong style="color: #ffffff;">' . htmlspecialchars($userName) . '</strong>,
                    </p>
                    
                    <p style="color: #9ca3af; line-height: 1.6; margin: 0 0 20px 0;">
                        Kami menerima permintaan untuk reset password akun Almai Anda. Klik tombol di bawah untuk membuat password baru:
                    </p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . $resetLink . '" style="display: inline-block; background-color: #33e818; color: #000000; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-weight: bold; font-size: 16px;">
                            Reset Password
                        </a>
                    </div>
                    
                    <p style="color: #9ca3af; line-height: 1.6; margin: 0 0 10px 0; font-size: 14px;">
                        Atau copy link berikut ke browser Anda:
                    </p>
                    <p style="color: #33e818; word-break: break-all; font-size: 12px; background-color: #000000; padding: 12px; border-radius: 8px; margin: 0 0 20px 0;">
                        ' . $resetLink . '
                    </p>
                    
                    <div style="background-color: rgba(251,191,36,0.1); border: 1px solid rgba(251,191,36,0.3); border-radius: 8px; padding: 16px; margin-top: 20px;">
                        <p style="color: #fbbf24; margin: 0; font-size: 14px;">
                            ⚠️ Link ini akan kadaluarsa dalam <strong>1 jam</strong>. Jika Anda tidak meminta reset password, abaikan email ini.
                        </p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style="text-align: center; margin-top: 30px;">
                    <p style="color: #6b7280; font-size: 12px; margin: 0;">
                        © ' . date('Y') . 'ALMAI ID. All rights reserved.
                    </p>
                    <p style="color: #6b7280; font-size: 12px; margin: 10px 0 0 0;">
                        Email ini dikirim secara otomatis, mohon tidak membalas email ini.
                    </p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * OTP email template
     */
    protected function getOtpTemplate(string $userName, string $otp): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
            <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" style="height: 50px;">
                </div>
                
                <!-- Content Card -->
                <div style="background-color: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <h2 style="color: #1a1a1a; text-align: center; margin: 0 0 10px 0; font-size: 24px;">Kode Verifikasi</h2>
                    <p style="color: #666666; text-align: center; margin: 0 0 30px 0; font-size: 14px;">Verifikasi akun ALMAI Anda</p>
                    
                    <p style="color: #333333; line-height: 1.6; margin: 0 0 10px 0;">
                        Halo <strong>' . htmlspecialchars($userName) . '</strong>,
                    </p>
                    
                    <p style="color: #666666; line-height: 1.6; margin: 0 0 30px 0;">
                        Gunakan kode OTP berikut untuk menyelesaikan pendaftaran akun Almai Anda:
                    </p>
                    
                    <!-- OTP Code -->
                    <div style="text-align: center; margin: 30px 0;">
                        <div style="display: inline-block; background: linear-gradient(135deg, #33e818 0%, #28b813 100%); border-radius: 12px; padding: 24px 48px;">
                            <span style="font-size: 32px; font-weight: bold; letter-spacing: 12px; color: #ffffff; font-family: monospace;">' . $otp . '</span>
                        </div>
                    </div>
                    
                    <div style="background-color: #fff8e6; border-left: 4px solid #f59e0b; border-radius: 4px; padding: 16px; margin-top: 30px;">
                        <p style="color: #92400e; margin: 0; font-size: 14px; line-height: 1.5;">
                            <strong>Penting:</strong> Kode ini akan kadaluarsa dalam 10 menit. Jangan bagikan kode ini kepada siapapun termasuk pihak yang mengaku dari ALMAI.
                        </p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style="text-align: center; margin-top: 30px;">
                    <p style="color: #999999; font-size: 12px; margin: 0;">
                        ' . date('Y') . ' ALMAI ID. All rights reserved.
                    </p>
                    <p style="color: #999999; font-size: 12px; margin: 10px 0 0 0;">
                        Jika Anda tidak mendaftar di Almai, abaikan email ini.
                    </p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Welcome email template
     */
    protected function getWelcomeTemplate(string $userName): string
    {
        $dashboardLink = base_url('user/dashboard');

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #050505;">
            <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" style="height: 50px;">
                    <h1 style="color: #ffffff; margin: 10px 0 0 0; font-size: 24px;">ALMAI</h1>
                </div>
                
                <!-- Content Card -->
                <div style="background-color: #111111; border-radius: 16px; padding: 40px; border: 1px solid rgba(255,255,255,0.1);">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <div style="width: 60px; height: 60px; background-color: rgba(51,232,24,0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                            <span style="font-size: 24px;">🎉</span>
                        </div>
                    </div>
                    
                    <h2 style="color: #ffffff; text-align: center; margin: 0 0 20px 0;">Selamat Datang!</h2>
                    
                    <p style="color: #9ca3af; line-height: 1.6; margin: 0 0 20px 0;">
                        Halo <strong style="color: #ffffff;">' . htmlspecialchars($userName) . '</strong>,
                    </p>
                    
                    <p style="color: #9ca3af; line-height: 1.6; margin: 0 0 20px 0;">
                        Selamat bergabung di ALMAI ID! Anda sekarang dapat mengakses berbagai kelas trading dari WPA bersertifikat.
                    </p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . $dashboardLink . '" style="display: inline-block; background-color: #33e818; color: #000000; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-weight: bold; font-size: 16px;">
                            Mulai Belajar
                        </a>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style="text-align: center; margin-top: 30px;">
                    <p style="color: #6b7280; font-size: 12px; margin: 0;">
                        © ' . date('Y') . 'ALMAI ID. All rights reserved.
                    </p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Transaction notification template
     */
    /**
     * Transaction notification template
     */
    protected function getTransactionTemplate(string $userName, array $transaction): string
    {
        return $this->renderInvoiceEmail($userName, $transaction, $transaction['status']);
    }

    /**
     * Send invoice pending payment email (Xendit)
     */
    public function sendInvoicePending(string $toEmail, string $userName, array $transaction, string $paymentUrl = null): array
    {
        $subject = 'Invoice Pembayaran #' . $transaction['invoice_number'] . ' - ALMAI ID';

        $message = $this->getInvoicePendingTemplate($userName, $transaction, $paymentUrl);

        return $this->send($toEmail, $subject, $message);
    }

    /**
     * Send payment success email with PDF attachment
     */
    public function sendPaymentSuccess(string $toEmail, string $userName, array $transaction, string $pdfPath = null): array
    {
        $subject = 'Pembayaran Berhasil #' . $transaction['invoice_number'] . ' - ALMAI ID';

        $message = $this->getPaymentSuccessTemplate($userName, $transaction);

        return $this->sendWithAttachment($toEmail, $subject, $message, $pdfPath);
    }

    /**
     * Send email with attachment
     */
    protected function sendWithAttachment(string $to, string $subject, string $message, string $attachmentPath = null): array
    {
        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($to);
            $this->email->setBCC($this->config->fromEmail); // BCC Admin
            $this->email->setSubject($subject);
            $this->email->setMessage($message);

            if ($attachmentPath && file_exists($attachmentPath)) {
                $this->email->attach($attachmentPath);
            }

            if ($this->email->send()) {
                return ['success' => true, 'message' => 'Email berhasil dikirim'];
            } else {
                $error = $this->email->printDebugger(['headers', 'subject', 'body']);
                log_message('error', 'Email send failed: ' . $error);
                return ['success' => false, 'message' => 'Gagal mengirim email', 'error' => $error];
            }
        } catch (\Exception $e) {
            log_message('error', 'Email exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Invoice pending payment template
     */
    protected function getInvoicePendingTemplate(string $userName, array $transaction, string $paymentUrl = null): string
    {
        return $this->renderInvoiceEmail($userName, $transaction, 'pending', $paymentUrl);
    }

    /**
     * Payment success template
     */
    protected function getPaymentSuccessTemplate(string $userName, array $transaction): string
    {
        return $this->renderInvoiceEmail($userName, $transaction, 'paid');
    }

    /**
     * Main Renderer for Invoice Emails
     */
    private function renderInvoiceEmail(string $userName, array $transaction, string $status, string $paymentUrl = null): string
    {
        $invoiceLink = base_url('user/invoice/' . $transaction['invoice_number']);

        // Configuration based on status
        $config = [
            'pending' => [
                'badge_text' => 'MENUNGGU PEMBAYARAN',
                'badge_color' => '#fbbf24', // Yellow text
                'badge_bg' => 'rgba(251, 191, 36, 0.1)',
                'status_box_bg' => 'rgba(251, 191, 36, 0.15)',
                'status_box_border' => 'none',
                'status_box_icon' => '🕒',
                'status_box_icon_bg' => '#fbbf24',
                'status_box_title' => 'Menunggu Pembayaran',
                'title_color' => '#fbbf24',
                'cta_text' => 'Bayar Sekarang',
                'cta_url' => $paymentUrl ?? $invoiceLink,
                'cta_color' => '#fbbf24' // Button color
            ],
            'paid' => [
                'badge_text' => 'TERBAYAR',
                'badge_color' => '#33E818',
                'badge_bg' => 'rgba(51, 232, 24, 0.1)',
                'status_box_bg' => 'rgba(51, 232, 24, 0.15)',
                'status_box_border' => 'none',
                'status_box_icon' => '✓',
                'status_box_icon_bg' => '#33E818',
                'status_box_title' => 'Pembayaran Berhasil',
                'title_color' => '#33E818',
                'cta_text' => 'Belajar Sekarang',
                'cta_url' => base_url('user/dashboard'),
                'cta_color' => '#33E818'
            ],
            'confirmed' => [ // Alias for paid
                'badge_text' => 'TERBAYAR',
                'badge_color' => '#33E818',
                'badge_bg' => 'rgba(51, 232, 24, 0.1)',
                'status_box_bg' => 'rgba(51, 232, 24, 0.15)',
                'status_box_border' => 'none',
                'status_box_icon' => '✓',
                'status_box_icon_bg' => '#33E818',
                'status_box_title' => 'Pembayaran Berhasil',
                'title_color' => '#33E818',
                'cta_text' => 'Belajar Sekarang',
                'cta_url' => base_url('user/dashboard'),
                'cta_color' => '#33E818'
            ],
            'cancelled' => [
                'badge_text' => 'DIBATALKAN',
                'badge_color' => '#ef4444',
                'badge_bg' => 'rgba(239, 68, 68, 0.1)',
                'status_box_bg' => 'rgba(239, 68, 68, 0.15)',
                'status_box_border' => 'none',
                'status_box_icon' => '✕',
                'status_box_icon_bg' => '#ef4444',
                'status_box_title' => 'Dibatalkan',
                'title_color' => '#ef4444',
                'cta_text' => 'Lihat Invoice',
                'cta_url' => $invoiceLink,
                'cta_color' => '#ef4444'
            ],
            'refunded' => [
                'badge_text' => 'DIBATALKAN',
                'badge_color' => '#ef4444',
                'badge_bg' => 'rgba(239, 68, 68, 0.1)',
                'status_box_bg' => 'rgba(239, 68, 68, 0.15)',
                'status_box_border' => 'none',
                'status_box_icon' => '✕',
                'status_box_icon_bg' => '#ef4444',
                'status_box_title' => 'Dikembalikan',
                'title_color' => '#ef4444',
                'cta_text' => 'Lihat Invoice',
                'cta_url' => $invoiceLink,
                'cta_color' => '#ef4444'
            ]
        ];

        $currentConfig = $config[$status] ?? $config['pending'];

        // Dates
        $dateCreate = date('d F Y', strtotime($transaction['created_at']));
        $dueDate = date('d M Y', strtotime($transaction['created_at'] . ' + 3 days')); // Approximation
        $updatedAt = date('d F Y, H:i', strtotime($transaction['updated_at'] ?? $transaction['created_at']));

        // Money formatting
        $price = number_format($transaction['amount'] ?? $transaction['total'], 0, ',', '.');
        $total = number_format($transaction['total'], 0, ',', '.');

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Invoice #' . $transaction['invoice_number'] . '</title>
        </head>
        <body style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; background-color: #1a1a1a;">
            <div style="width: 100%; background-color: #1a1a1a; padding: 40px 0;">
                <div style="max-width: 500px; margin: 0 auto; background-color: #09090b; border-radius: 20px; padding: 30px; border: 1px solid rgba(255,255,255,0.1); color: #ffffff;">
                    
                    <!-- Header -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 30px;">
                        <tr>
                            <td valign="top">
                                <div style="display: flex; align-items: center;">
                                    <div style="width: 32px; height: 32px; background-color: #33E818; border-radius: 50%; display: inline-block; text-align: center; line-height: 32px; margin-right: 8px;">
                                        <span style="color: #000; font-weight: bold; font-size: 18px;">A</span>
                                    </div>
                                    <span style="font-size: 20px; font-weight: bold; color: #fff; vertical-align: middle;">Almai</span>
                                </div>
                            </td>
                            <td align="right" valign="top">
                                <div style="background-color: ' . $currentConfig['badge_bg'] . '; color: ' . $currentConfig['badge_color'] . '; font-size: 10px; font-weight: bold; padding: 4px 8px; border-radius: 4px; display: inline-block; margin-bottom: 4px;">
                                    ' . $currentConfig['badge_text'] . '
                                </div>
                                <div style="color: #666; font-size: 12px; font-family: monospace;">#' . $transaction['invoice_number'] . '</div>
                            </td>
                        </tr>
                    </table>

                    <!-- Greeting -->
                    <div style="margin-bottom: 30px;">
                        <p style="color: #e5e5e5; font-size: 14px; margin: 0 0 8px 0;">Halo ' . htmlspecialchars(explode(' ', $userName)[0]) . ',</p>
                        <p style="color: #888; font-size: 12px; line-height: 1.6; margin: 0;">
                            Terima kasih telah mempercayakan pembelian Anda kepada ALMAI. Berikut kami sertakan invoice sebagai detail transaksi Anda.
                        </p>
                    </div>

                    <!-- Details Grid -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 30px;">
                        <tr>
                            <td width="50%" valign="top" style="padding-bottom: 20px;">
                                <p style="color: #666; font-size: 10px; font-weight: bold; text-transform: uppercase; margin: 0 0 8px 0; letter-spacing: 0.5px;">Invoice From</p>
                                <p style="color: #fff; font-weight: bold; font-size: 11px; margin: 0 0 2px 0;">PT. Alma Indonesia Raya</p>
                                <p style="color: #888; font-size: 11px; margin: 0 0 2px 0;">Jl. Badak Agung No. 22 Kav. 3, Renon, Denpasar - Bali. 80226</p>
                                <p style="color: #888; font-size: 11px; margin: 0;">Telp: 0361-3610019</p>
                            </td>
                            <td width="50%" valign="top" style="padding-bottom: 20px;">
                                <!-- Spacer or Empty for layout match -->
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" valign="top">
                                <p style="color: #666; font-size: 10px; font-weight: bold; text-transform: uppercase; margin: 0 0 8px 0; letter-spacing: 0.5px;">Invoice To</p>
                                <p style="color: #fff; font-weight: bold; font-size: 11px; margin: 0 0 2px 0;">' . htmlspecialchars($userName) . '</p>
                                <p style="color: #888; font-size: 11px; margin: 0;">' . htmlspecialchars($transaction['user_email'] ?? '') . '</p>
                            </td>
                            <td width="50%" valign="top">
                                <p style="color: #666; font-size: 10px; font-weight: bold; text-transform: uppercase; margin: 0 0 8px 0; letter-spacing: 0.5px;">Metode Pembayaran</p>
                                <p style="color: #fff; font-weight: bold; font-size: 11px; margin: 0; text-transform: uppercase;">' . htmlspecialchars($transaction['payment_method'] ?? 'QRIS') . '</p>
                            </td>
                        </tr>
                    </table>

                     <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 30px;">
                        <tr>
                            <td width="50%" valign="top">
                                <p style="color: #666; font-size: 10px; font-weight: bold; text-transform: uppercase; margin: 0 0 8px 0; letter-spacing: 0.5px;">Date Create</p>
                                <p style="color: #fff; font-size: 11px; margin: 0;">' . $dateCreate . '</p>
                            </td>
                            <td width="50%" valign="top">
                                <p style="color: #666; font-size: 10px; font-weight: bold; text-transform: uppercase; margin: 0 0 8px 0; letter-spacing: 0.5px;">Due Date</p>
                                <p style="color: #fff; font-size: 11px; margin: 0;">' . $dueDate . '</p>
                            </td>
                        </tr>
                    </table>

                    <!-- Items Table -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 20px;">
                        <!-- Head -->
                        <tr>
                            <td width="10%" style="color: #666; font-size: 10px; font-weight: bold; text-transform: uppercase; padding-bottom: 10px;">#</td>
                            <td width="60%" style="color: #666; font-size: 10px; font-weight: bold; text-transform: uppercase; padding-bottom: 10px;">Qty</td>
                            <td width="30%" align="right" style="color: #666; font-size: 10px; font-weight: bold; text-transform: uppercase; padding-bottom: 10px;">Harga</td>
                        </tr>
                        <!-- Divider -->
                        <tr>
                            <td colspan="3" style="border-top: 1px dashed rgba(255,255,255,0.1);"></td>
                        </tr>
                        <!-- Item -->
                        <tr>
                            <td style="padding-top: 15px; vertical-align: top; color: #888; font-size: 11px; font-weight: bold;">1</td>
                            <td style="padding-top: 15px; vertical-align: top;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <!-- Using display inline-block hack with span for email compatibility? Better nested table or just simple formatting -->
                                        <td valign="middle">
                                             <span style="background-color: rgba(51, 232, 24, 0.1); color: #33E818; font-size: 8px; font-weight: bold; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; margin-right: 8px; border: 1px solid rgba(51, 232, 24, 0.2);">' . htmlspecialchars($transaction['product_type'] ?? 'Layanan') . '</span>
                                        </td>
                                        <td valign="middle">
                                            <span style="color: #fff; font-size: 11px; font-weight: 500;">' . htmlspecialchars($transaction['product_name']) . '</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td align="right" style="padding-top: 15px; vertical-align: top; color: #ddd; font-size: 11px; font-weight: bold;">Rp ' . $price . '</td>
                        </tr>
                        <!-- Divider Bottom -->
                         <tr>
                            <td colspan="3" style="padding-top: 15px; border-bottom: 1px dashed rgba(255,255,255,0.1);"></td>
                        </tr>
                    </table>

                    <!-- Calculations -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 30px;">
                        <tr>
                            <td width="60%"></td>
                            <td width="40%">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="color: #888; font-size: 11px; padding-bottom: 5px;">SubTotal</td>
                                        <td align="right" style="color: #fff; font-size: 11px; font-weight: bold; padding-bottom: 5px;">Rp ' . $price . '</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #888; font-size: 11px; padding-bottom: 5px;">Diskon</td>
                                        <td align="right" style="color: #ef4444; font-size: 11px; font-weight: bold; padding-bottom: 5px;">-Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #888; font-size: 11px; padding-bottom: 10px;">PPN</td>
                                        <td align="right" style="color: #888; font-size: 11px; padding-bottom: 10px;">-</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #e5e5e5; font-size: 13px; font-weight: bold;">Total</td>
                                        <td align="right" style="color: #fff; font-size: 13px; font-weight: bold;">Rp ' . $total . '</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                     <!-- Separator Line -->
                    <div style="width: 50px; height: 4px; background-color: #333; border-radius: 2px; margin-bottom: 25px;"></div>

                    <!-- Status Notification -->
                    <div style="background-color: ' . $currentConfig['status_box_bg'] . '; border-radius: 12px; padding: 12px; display: flex; align-items: flex-start; margin-bottom: 25px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="30" valign="top">
                                    <div style="width: 24px; height: 24px; background-color: ' . $currentConfig['status_box_icon_bg'] . '; border-radius: 50%; color: #000; text-align: center; line-height: 24px; font-size: 12px; font-weight: bold;">
                                        ' . $currentConfig['status_box_icon'] . '
                                    </div>
                                </td>
                                <td>
                                    <div style="color: ' . $currentConfig['title_color'] . '; font-size: 11px; font-weight: bold; margin-bottom: 2px;">' . $currentConfig['status_box_title'] . '</div>
                                    <div style="color: #888; font-size: 9px;">Dikonfirmasi pada: ' . $updatedAt . '</div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Specific Advocacy Section -->
                    ' . ((strpos(strtolower($transaction['product_name']), 'advokasi') !== false && in_array($status, ['paid', 'confirmed'])) ? '
                    <div style="background-color: rgba(51, 232, 24, 0.05); border: 1px solid rgba(51, 232, 24, 0.2); border-radius: 16px; padding: 20px; margin-bottom: 25px; text-align: center;">
                        <span style="font-size: 24px; display: block; margin-bottom: 10px;">🛡️</span>
                        <h4 style="color: #33E818; margin: 0 0 10px 0; font-size: 14px;">Lengkapi Detail Advokasi</h4>
                        <p style="color: #888; font-size: 12px; line-height: 1.5; margin: 0 0 20px 0;">
                            Pembayaran Anda telah diterima. Silakan lengkapi data Identitas, Kronologi kejadian, dan lampirkan Bukti pendukung agar tim kami dapat segera melakukan proses investigasi.
                        </p>
                        <a href="' . base_url('user/advokasi') . '" style="display: inline-block; background-color: #33E818; color: #000; text-decoration: none; padding: 10px 24px; border-radius: 50px; font-weight: bold; font-size: 12px;">
                            Isi Detail Sekarang
                        </a>
                    </div>' : '') . '

                    <!-- CTA Button -->
                    <div style="text-align: center; margin-bottom: 30px;">
                        <a href="' . $currentConfig['cta_url'] . '" style="display: block; width: 100%; background-color: ' . $currentConfig['cta_color'] . '; color: #000000; text-decoration: none; padding: 14px 0; border-radius: 12px; font-weight: bold; font-size: 13px;">
                            ' . $currentConfig['cta_text'] . '
                        </a>
                    </div>

                    <!-- Footer Notes -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 15px;">
                        <tr>
                            <td valign="top">
                                <p style="color: #666; font-size: 9px; font-weight: bold; text-transform: uppercase; margin: 0 0 4px 0;">NOTES</p>
                                <p style="color: #888; font-size: 9px; margin: 0;">Menggunakan kode referral: <span style="color: #33E818;">' . htmlspecialchars($transaction['referral_code'] ?? 'ardiansyah') . '</span></p>
                            </td>
                            <td align="right" valign="top">
                                <p style="color: #666; font-size: 9px; font-weight: bold; text-transform: uppercase; margin: 0 0 4px 0;">Butuh bantuan?</p>
                                <p style="color: #888; font-size: 9px; margin: 0;">support@almai.id</p>
                            </td>
                        </tr>
                    </table>

                </div>
                 <!-- Footer Copyright -->
                 <div style="text-align: center; margin-top: 20px;">
                    <p style="color: #444; font-size: 10px;">© ' . date('Y') . ' ALMAI ID. All rights reserved.</p>
                 </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Account credentials email template (for admin-created users)
     */
    protected function getAccountCredentialsTemplate(string $userName, string $email, string $password, string $programName = 'Scalpinghack'): string
    {
        $loginLink = base_url('login');

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
            <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" style="height: 50px;">
                    <h1 style="color: #1a1a1a; margin: 10px 0 0 0; font-size: 24px;">ALMAI ID</h1>
                    <p style="color: #666666; margin: 5px 0 0 0; font-size: 12px;">PT. Alma Indonesia Raya - Penasihat Berjangka</p>
                </div>
                
                <!-- Content Card -->
                <div style="background-color: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="width: 60px; height: 60px; background-color: rgba(51,232,24,0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                            <span style="font-size: 24px;">✅</span>
                        </div>
                    </div>
                    
                    <h2 style="color: #1a1a1a; text-align: center; margin: 0 0 10px 0; font-size: 20px;">Akun Anda Sudah Aktif</h2>
                    <p style="color: #666666; text-align: center; margin: 0 0 30px 0; font-size: 13px;">Informasi Login Akun ALMAI</p>
                    
                    <p style="color: #333333; line-height: 1.6; margin: 0 0 10px 0;">
                        Halo <strong>' . htmlspecialchars($userName) . '</strong>,
                    </p>
                    
                    <p style="color: #666666; line-height: 1.6; margin: 0 0 20px 0; font-size: 14px;">
                        Email ini menginfokan bahwa akun Anda telah aktif untuk mengikuti program milik <strong>' . htmlspecialchars($programName) . '</strong> atau layanan dari <strong>PT. Alma Indonesia Raya</strong>.
                    </p>

                    <p style="color: #333333; line-height: 1.6; margin: 0 0 20px 0; font-size: 14px;">
                        Akun Anda di platform <strong>almai.id</strong> sudah <span style="color: #33e818; font-weight: bold;">AKTIF</span>. Berikut adalah kredensial akun Anda:
                    </p>
                    
                    <!-- Credentials Box -->
                    <div style="background-color: #1a1a1a; border: 2px solid #33e818; border-radius: 16px; padding: 28px; margin: 24px 0; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1;">
                                <p style="color: #ffffff; margin: 0 0 20px 0; font-size: 18px; font-weight: bold;">Your Account</p>
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="color: #999999; padding: 10px 0; font-size: 14px; width: 100px;">Email</td>
                                        <td style="color: #ffffff; padding: 10px 0; font-weight: normal; font-size: 14px;">: ' . htmlspecialchars($email) . '</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #999999; padding: 10px 0; font-size: 14px;">Password</td>
                                        <td style="color: #ffffff; padding: 10px 0; font-weight: bold; font-family: monospace; font-size: 18px; letter-spacing: 2px;">: ' . htmlspecialchars($password) . '</td>
                                    </tr>
                                </table>
                            </div>
                            <div style="margin-left: 20px; flex-shrink: 0;">
                                <img src="https://almai.id/images/almaiman-mascot.png" alt="Almaiman" style="width: 100px; height: auto; display: block;" />
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice --小>
                    <p style="color: #666666; line-height: 1.6; margin: 24px 0; font-size: 14px;">
                        Demi menjaga keamanan akun Anda, kami menyarankan untuk segera mengganti password setelah login pertama kali. Jika lupa password, silakan klik <strong>Lupa Password</strong>.
                    </p>
                    
                    <div style="text-align: center; margin: 32px 0;">
                        <a href="' . $loginLink . '" style="display: inline-block; background-color: #33e818; color: #000000; text-decoration: none; padding: 16px 60px; border-radius: 12px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 16px rgba(51,232,24,0.4);">
                            Login Sekarang
                        </a>
                    </div>

                    <div style="background-color: #f9fafb; border-radius: 12px; padding: 20px; margin: 24px 0;">
                        <p style="color: #666666; font-size: 13px; margin: 0 0 8px 0; font-weight: bold;">NOTES</p>
                        <p style="color: #666666; font-size: 12px; margin: 0; line-height: 1.6;">
                            Jika ada pertanyaan, silahkan hubungi kami melalui:<br>
                            WhatsApp: +62 851-8323-1800
                        </p>
                    </div>

                    <div style="text-align: right; margin-top: 24px;">
                        <p style="color: #666666; font-size: 13px; margin: 0;">
                            Butuh bantuan? <a href="mailto:support@almai.id" style="color: #33e818; text-decoration: none;">support@almai.id</a>
                        </p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style="text-align: center; margin-top: 40px;">
                    <p style="color: #999999; font-size: 11px; margin: 0;">
                        Salam sukses,<br>
                        <strong>Customer Support</strong><br>
                        <span style="font-size: 10px;">TRUSTED | SPECIALIST | EFFICIENT</span>
                    </p>
                    <p style="color: #999999; font-size: 10px; margin: 15px 0 0 0;">
                        © ' . date('Y') . ' PT. Alma Indonesia Raya. All rights reserved.
                    </p>
                    <p style="color: #cccccc; font-size: 10px; margin: 5px 0 0 0;">
                        Email ini dikirim secara otomatis, mohon tidak membalas email ini.
                    </p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Send CWPA Registration Confirmation
     */
    public function sendCwpaRegistrationConfirmation(string $toEmail, string $userName, array $submissionData): array
    {
        $subject = 'Konfirmasi Pendaftaran CWPA - ALMAI ID';
        $message = $this->getCwpaRegistrationTemplate($userName, $submissionData);

        return $this->send($toEmail, $subject, $message);
    }

    /**
     * CWPA Registration Template
     */
    protected function getCwpaRegistrationTemplate(string $userName, array $submissionData): string
    {
        $loginLink = base_url('login');

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #050505;">
            <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" style="height: 50px;">
                    <h1 style="color: #ffffff; margin: 10px 0 0 0; font-size: 24px;">ALMAI</h1>
                </div>
                
                <!-- Content Card -->
                <div style="background-color: #111111; border-radius: 16px; padding: 40px; border: 1px solid rgba(255,255,255,0.1);">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <div style="width: 60px; height: 60px; background-color: rgba(51,232,24,0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                            <span style="font-size: 24px;">📝</span>
                        </div>
                    </div>
                    
                    <h2 style="color: #ffffff; text-align: center; margin: 0 0 20px 0;">Pendaftaran Diterima</h2>
                    
                    <p style="color: #9ca3af; line-height: 1.6; margin: 0 0 20px 0;">
                        Halo <strong style="color: #ffffff;">' . htmlspecialchars($userName) . '</strong>,
                    </p>
                    
                    <p style="color: #9ca3af; line-height: 1.6; margin: 0 0 20px 0;">
                        Terima kasih telah mendaftar sebagai Calon Wakil Penasihat Berjangka (CWPA) di PT. Alma Indonesia Raya. Kami telah menerima data dan dokumen pendaftaran Anda.
                    </p>
                    
                    <div style="background-color: rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin: 20px 0;">
                        <h3 style="color: #ffffff; margin: 0 0 15px 0; font-size: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">Ringkasan Data</h3>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="color: #6b7280; font-size: 14px; padding-bottom: 8px; width: 140px;">Nama Lengkap</td>
                                <td style="color: #ffffff; font-size: 14px; padding-bottom: 8px;">: ' . htmlspecialchars($submissionData['name'] ?? $userName) . '</td>
                            </tr>
                            <tr>
                                <td style="color: #6b7280; font-size: 14px; padding-bottom: 8px;">Email</td>
                                <td style="color: #ffffff; font-size: 14px; padding-bottom: 8px;">: ' . htmlspecialchars($submissionData['email']) . '</td>
                            </tr>
                            <tr>
                                <td style="color: #6b7280; font-size: 14px; padding-bottom: 8px;">No. WhatsApp</td>
                                <td style="color: #ffffff; font-size: 14px; padding-bottom: 8px;">: ' . htmlspecialchars($submissionData['whatsapp']) . '</td>
                            </tr>
                             <tr>
                                <td style="color: #6b7280; font-size: 14px; padding-bottom: 8px;">Spesialisasi</td>
                                <td style="color: #ffffff; font-size: 14px; padding-bottom: 8px;">: ' . htmlspecialchars($submissionData['specialties'] ?? '-') . '</td>
                            </tr>
                        </table>
                    </div>

                    <p style="color: #9ca3af; line-height: 1.6; margin: 0 0 20px 0;">
                        Langkah selanjutnya adalah menyelesaikan pembayaran administrasi pendaftaran (jika belum). Tim kami akan memverifikasi dokumen Anda setelah pembayaran dikonfirmasi.
                    </p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . base_url('user/dashboard') . '" style="display: inline-block; background-color: #33e818; color: #000000; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-weight: bold; font-size: 16px;">
                           Cek Status Pendaftaran
                        </a>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style="text-align: center; margin-top: 30px;">
                    <p style="color: #6b7280; font-size: 12px; margin: 0;">
                        © ' . date('Y') . ' ALMAI ID. All rights reserved.
                    </p>
                    <p style="color: #6b7280; font-size: 12px; margin: 10px 0 0 0;">
                        Email ini dikirim secara otomatis, mohon tidak membalas email ini.
                    </p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Guest credentials email template
     */
    protected function getGuestCredentialsTemplate(string $userName, string $email, ?string $password = null): string
    {
        $loginLink = base_url('login');

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #050505;">
            <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" style="height: 50px;">
                    <h1 style="color: #ffffff; margin: 10px 0 0 0; font-size: 24px;">ALMAI</h1>
                </div>
                
                <!-- Content Card -->
                <div style="background-color: #111111; border-radius: 16px; padding: 40px; border: 1px solid rgba(255,255,255,0.1);">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <div style="width: 60px; height: 60px; background-color: rgba(51,232,24,0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                            <span style="font-size: 24px;">🔑</span>
                        </div>
                    </div>
                    
                    <h2 style="color: #ffffff; text-align: center; margin: 0 0 20px 0;">Akun Baru Anda</h2>
                    
                    <p style="color: #9ca3af; line-height: 1.6; margin: 0 0 20px 0;">
                        Halo <strong style="color: #ffffff;">' . htmlspecialchars($userName) . '</strong>,
                    </p>
                    
                    <p style="color: #9ca3af; line-height: 1.6; margin: 0 0 20px 0;">
                        Terima kasih telah melakukan pembelian. Akun Anda telah dibuat secara otomatis. Silakan gunakan kredensial berikut untuk login:
                    </p>
                    
                    <div style="background-color: #000000; border-radius: 12px; padding: 24px; margin: 20px 0; border: 1px solid #333;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="color: #9ca3af; padding: 8px 0;">Email</td>
                                <td style="color: #ffffff; text-align: right; padding: 8px 0; font-weight: bold;">' . $email . '</td>
                            </tr>
                            <tr>
                                <td style="color: #9ca3af; padding: 8px 0;">Password</td>
                                <td style="color: #33e818; text-align: right; padding: 8px 0; font-weight: bold; font-family: monospace; font-size: 16px;">' . ($password ? $password : '<span style="color: #666; font-style: italic; font-weight: normal;">(Password yang Anda buat)</span>') . '</td>
                            </tr>
                        </table>
                    </div>

                    <p style="color: #fbbf24; font-size: 14px; text-align: center; margin-bottom: 30px;">
                        Demi keamanan, kami sarankan Anda segera mengganti password setelah login pertama kali.
                        <br>(Klik Lupa Password jika Anda lupa)
                    </p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . $loginLink . '" style="display: inline-block; background-color: #33e818; color: #000000; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-weight: bold; font-size: 16px;">
                            Login Sekarang
                        </a>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style="text-align: center; margin-top: 30px;">
                    <p style="color: #6b7280; font-size: 12px; margin: 0;">
                        © ' . date('Y') . ' ALMAI ID. All rights reserved.
                    </p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Send legal documents (Perjanjian, Risiko, Profil, WPA)
     */
    public function sendLegalDocuments(string $toEmail, string $userName, array $attachments, string $invoiceNumber): array
    {
        $subject = 'Dokumen Perjanjian & Profil Perusahaan - ALMAI ID';

        $message = $this->getLegalDocumentsTemplate($userName, $invoiceNumber, count($attachments));

        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($toEmail);
            $this->email->setBCC($this->config->fromEmail); // BCC Admin
            $this->email->setSubject($subject);
            $this->email->setMessage($message);

            // Attach all files
            foreach ($attachments as $name => $path) {
                if (file_exists($path)) {
                    $this->email->attach($path, 'attachment', $name . '.pdf');
                }
            }

            if ($this->email->send()) {
                return ['success' => true, 'message' => 'Dokumen legal berhasil dikirim'];
            } else {
                $error = $this->email->printDebugger(['headers', 'subject', 'body']);
                log_message('error', 'Legal documents email send failed: ' . $error);
                return ['success' => false, 'message' => 'Gagal mengirim dokumen legal', 'error' => $error];
            }
        } catch (\Exception $e) {
            log_message('error', 'Legal documents email exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Legal documents email template
     */
    protected function getLegalDocumentsTemplate(string $userName, string $invoiceNumber, int $docCount = 2): string
    {
        $docsHtml = '
            <li><strong style="color: #fff;">Perjanjian Pemberian Jasa</strong> - Dokumen kontrak layanan penasihat berjangka</li>
            <li><strong style="color: #fff;">Dokumen Pemberitahuan Risiko</strong> - Informasi risiko perdagangan derivatif</li>';

        if ($docCount > 2) {
            $docsHtml .= '
                <li><strong style="color: #fff;">Profil Perusahaan</strong> - Informasi lengkap profil PT. Alma Indonesia Raya</li>
                <li><strong style="color: #fff;">Perjanjian WPA</strong> - Dokumen kesepakatan khusus program CWPA</li>';
        }

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #050505;">
            <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" style="height: 50px;">
                    <h1 style="color: #33E818; margin: 20px 0 10px 0; font-size: 24px;">Dokumen Perjanjian Anda</h1>
                    <p style="color: #888; margin: 0; font-size: 14px;">Invoice: ' . htmlspecialchars($invoiceNumber) . '</p>
                </div>

                <!-- Content -->
                <div style="background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 30px; margin-bottom: 20px;">
                    <p style="color: #fff; margin: 0 0 20px 0; font-size: 16px;">Halo <strong>' . htmlspecialchars($userName) . '</strong>,</p>
                    
                    <p style="color: #ccc; line-height: 1.6; margin: 0 0 20px 0;">
                        Terima kasih telah melakukan pembayaran. Terlampir adalah dokumen-dokumen penting terkait layanan Anda:
                    </p>

                    <div style="background: rgba(51,232,24,0.1); border: 1px solid rgba(51,232,24,0.3); border-radius: 12px; padding: 20px; margin: 20px 0;">
                        <h3 style="color: #33E818; margin: 0 0 15px 0; font-size: 16px;">📄 Dokumen Terlampir:</h3>
                        <ul style="color: #ccc; margin: 0; padding-left: 20px; line-height: 1.8;">
                            ' . $docsHtml . '
                        </ul>
                    </div>

                    <div style="background: rgba(255,193,7,0.1); border: 1px solid rgba(255,193,7,0.3); border-radius: 12px; padding: 15px; margin: 20px 0;">
                        <p style="color: #ffc107; margin: 0; font-size: 13px;">
                            <strong>⚠️ Penting:</strong> Harap simpan dokumen-dokumen ini dengan baik. Dokumen ini memiliki kekuatan hukum yang sah dan mengikat.
                        </p>
                    </div>

                    <p style="color: #ccc; line-height: 1.6; margin: 20px 0 0 0;">
                        Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi tim support kami.
                    </p>
                </div>

                <!-- CTA Button -->
                <div style="text-align: center; margin: 30px 0;">
                    <a href="https://almai.id/user/transaksi" style="display: inline-block; background: #33E818; color: #000; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: bold; font-size: 14px;">
                        Lihat Transaksi Saya
                    </a>
                </div>

                <!-- Footer -->
                <div style="text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <p style="color: #666; font-size: 12px; margin: 0 0 10px 0;">
                        PT. Alma Indonesia Raya<br>
                        Jl. Badak Agung No. 22 Kav. 3, Denpasar - Bali<br>
                        Email: cs@almai.id | Phone: 0361 361 0019
                    </p>
                    <p style="color: #666; font-size: 11px; margin: 10px 0 0 0;">
                        © 2026 ALMAI. All rights reserved.
                    </p>
                </div>
            </div>
        </body>
        </html>';
    }
}
