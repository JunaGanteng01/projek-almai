<?php

namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $table = 'otp_codes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'phone', 'otp', 'type', 'expires_at', 'used', 'created_at'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Generate and store OTP
     */
    public function generateOtp(string $identifier, string $type = 'registration', string $channel = 'email'): string
    {
        // Determine field based on channel or identifier format
        $cleanIdentifier = str_replace(['+', '-', ' '], '', $identifier);
        $field = $channel === 'whatsapp' || is_numeric($cleanIdentifier) ? 'phone' : 'email';

        // Invalidate existing OTPs for this identifier and type
        $this->where($field, $identifier)
            ->where('type', $type)
            ->where('used', 0)
            ->set(['used' => 1])
            ->update();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $data = [
            'otp' => $otp,
            'type' => $type,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+10 minutes')),
            'used' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // dynamic field assignment
        $data[$field] = $identifier;
        if ($field === 'phone') {
            $data['email'] = null; // Ensure email is null if using phone, if schema allows null
            // If schema doesn't allow null email, we might need a dummy email or check schema.
            // Assumption: User will handle database migration for 'phone' column.
        }

        $this->insert($data);

        return $otp;
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(string $identifier, string $otp, string $type = 'registration'): bool
    {
        // Check if identifier is likely a phone or email
        $field = is_numeric(str_replace(['+', '-', ' '], '', $identifier)) ? 'phone' : 'email';

        $record = $this->where($field, $identifier)
            ->where('otp', $otp)
            ->where('type', $type)
            ->whereIn('used', [0, 2])
            ->where('expires_at >', date('Y-m-d H:i:s', strtotime('-30 seconds')))
            ->first();

        if ($record) {
            // Mark as used
            $this->update($record['id'], ['used' => 1]);
            return true;
        }

        return false;
    }

    /**
     * Mark OTP as verified via WhatsApp Webhook
     */
    public function markAsVerifiedViaWa(string $phone, string $otp): bool
    {
        $phoneClean = preg_replace('/[^0-9]/', '', $phone);
        $phoneVariants = [$phone, $phoneClean];
        if (substr($phoneClean, 0, 1) === '0') {
            $phoneVariants[] = '62' . substr($phoneClean, 1);
        } elseif (substr($phoneClean, 0, 2) === '62') {
            $phoneVariants[] = '0' . substr($phoneClean, 2);
        }

        $record = $this->whereIn('phone', $phoneVariants)
            ->where('otp', $otp)
            ->where('used', 0)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->first();

        if ($record) {
            $this->update($record['id'], ['used' => 2]);
            return true;
        }

        return false;
    }

    /**
     * Check if phone has a WA verified OTP
     */
    public function isWaVerified(string $phone): bool
    {
        $phoneClean = preg_replace('/[^0-9]/', '', $phone);
        $phoneVariants = [$phone, $phoneClean];
        if (substr($phoneClean, 0, 1) === '0') {
            $phoneVariants[] = '62' . substr($phoneClean, 1);
        } elseif (substr($phoneClean, 0, 2) === '62') {
            $phoneVariants[] = '0' . substr($phoneClean, 2);
        }

        return $this->whereIn('phone', $phoneVariants)
            ->where('used', 2)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->countAllResults() > 0;
    }

    /**
     * Check if OTP exists and is valid (without marking as used)
     */
    public function isValidOtp(string $email, string $otp, string $type = 'registration'): bool
    {
        return $this->where('email', $email)
            ->where('otp', $otp)
            ->where('type', $type)
            ->where('used', 0)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->countAllResults() > 0;
    }

    /**
     * Clean expired OTPs
     */
    public function cleanExpired(): int
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
}
