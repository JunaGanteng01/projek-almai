<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * LevelModel
 *
 * Sumber tunggal (single source of truth) untuk logika role/level.
 * Semua mapping level_id ke dashboard, nama role, badge, dan cek akses
 * harus lewat class ini supaya tidak tersebar dan mudah diaudit.
 *
 * Catatan: session masih menyimpan userRole (string legacy) untuk
 * kompatibilitas filter yang sudah jalan. Gunakan helper konversi di sini
 * agar level_id tetap jadi acuan utama.
 */
class LevelModel extends Model
{
    protected $table = 'levels';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['name'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    // Default levels based on levels.sql
    public const LEVEL_USER = 1;
    public const LEVEL_PRO = 2;
    public const LEVEL_CWPA = 3;
    public const LEVEL_WPA = 4;
    public const LEVEL_ADMIN = 5;
    public const LEVEL_ACCOUNTING = 6;
    public const LEVEL_SUPER_ADMIN = 7;
    public const LEVEL_ADMIN_WPA = 8;
    public const LEVEL_PARTNERSHIP = 9;
    public const LEVEL_CEO = 10;

    // ------------------------------------------------------------------
    // MAPPING DATA
    // ------------------------------------------------------------------

    /**
     * Map level_id ke segment prefix dashboard (tanpa trailing slash).
     * Super Admin (7) -> /superadmin (terpisah dari Admin).
     * Accounting (6) -> /keuangan (bukan typo /keuangna).
     */
    private static array $DASHBOARD_SEGMENT = [
        self::LEVEL_USER         => 'user',
        self::LEVEL_PRO          => 'user',
        self::LEVEL_CWPA         => 'cwpa',
        self::LEVEL_WPA          => 'wpa',
        self::LEVEL_ADMIN        => 'admin',
        self::LEVEL_ACCOUNTING   => 'keuangan',
        self::LEVEL_SUPER_ADMIN  => 'superadmin',
        self::LEVEL_ADMIN_WPA    => 'admin-wpa',
        self::LEVEL_PARTNERSHIP  => 'laporan-kegiatan',
        self::LEVEL_CEO          => 'ceo',
    ];

    /**
     * Map level_id ke nama role yang ditampilkan ke user.
     */
    private static array $ROLE_NAME = [
        self::LEVEL_USER         => 'User',
        self::LEVEL_PRO          => 'User PRO',
        self::LEVEL_CWPA         => 'CWPA',
        self::LEVEL_WPA          => 'WPA',
        self::LEVEL_ADMIN        => 'Admin',
        self::LEVEL_ACCOUNTING   => 'Accounting',
        self::LEVEL_SUPER_ADMIN  => 'Super Admin',
        self::LEVEL_ADMIN_WPA    => 'Admin WPA',
        self::LEVEL_PARTNERSHIP  => 'Admin Partnership',
        self::LEVEL_CEO          => 'CEO',
    ];

    /**
     * Map level_id ke class badge Tailwind (konsisten di semua view).
     */
    private static array $ROLE_BADGE = [
        self::LEVEL_USER         => 'bg-accent/20 text-accent',
        self::LEVEL_PRO          => 'bg-yellow-500/20 text-yellow-400',
        self::LEVEL_CWPA         => 'bg-green-500/20 text-green-400',
        self::LEVEL_WPA          => 'bg-blue-500/20 text-blue-400',
        self::LEVEL_ADMIN        => 'bg-purple-500/20 text-purple-400',
        self::LEVEL_ACCOUNTING   => 'bg-purple-500/20 text-purple-400',
        self::LEVEL_SUPER_ADMIN  => 'bg-purple-500/20 text-purple-400',
        self::LEVEL_ADMIN_WPA    => 'bg-yellow-100/20 text-yellow-500',
        self::LEVEL_PARTNERSHIP  => 'bg-blue-100/20 text-blue-500',
        self::LEVEL_CEO          => 'bg-emerald-500/20 text-emerald-400',
    ];

    /**
     * Map string role legacy (session userRole) ke level_id.
     * Dipakai untuk konversi mundur agar filter lama tetap jalan.
     */
    private static array $ROLE_STRING_TO_LEVEL = [
        'user'              => self::LEVEL_USER,
        'user_pro'          => self::LEVEL_PRO,
        'pro'               => self::LEVEL_PRO,
        'cwpa'              => self::LEVEL_CWPA,
        'wpa'               => self::LEVEL_WPA,
        'admin'             => self::LEVEL_ADMIN,
        'accounting'        => self::LEVEL_ACCOUNTING,
        'superadmin'        => self::LEVEL_SUPER_ADMIN,
        'admin_wpa'         => self::LEVEL_ADMIN_WPA,
        'admin-wpa'         => self::LEVEL_ADMIN_WPA,
        'admin-partnership' => self::LEVEL_PARTNERSHIP,
        'partnership'       => self::LEVEL_PARTNERSHIP,
        'partnership_admin' => self::LEVEL_PARTNERSHIP,
        'ceo'               => self::LEVEL_CEO,
    ];

    /**
     * Map level_id ke string role legacy (session userRole).
     * Super Admin (7) memetakan ke 'admin' karena share prefix /admin.
     */
    private static array $LEVEL_TO_ROLE_STRING = [
        self::LEVEL_USER         => 'user',
        self::LEVEL_PRO          => 'user_pro',
        self::LEVEL_CWPA         => 'cwpa',
        self::LEVEL_WPA          => 'wpa',
        self::LEVEL_ADMIN        => 'admin',
        self::LEVEL_ACCOUNTING   => 'accounting',
        self::LEVEL_SUPER_ADMIN  => 'admin',
        self::LEVEL_ADMIN_WPA    => 'admin_wpa',
        self::LEVEL_PARTNERSHIP  => 'admin-partnership',
        self::LEVEL_CEO          => 'ceo',
    ];

    // ------------------------------------------------------------------
    // DASHBOARD HELPERS
    // ------------------------------------------------------------------

    public static function getDashboardSegment(int $levelId): string
    {
        return self::$DASHBOARD_SEGMENT[$levelId] ?? 'user';
    }

    public static function getDashboardPath(int $levelId): string
    {
        return '/' . self::getDashboardSegment($levelId) . '/dashboard';
    }

    // ------------------------------------------------------------------
    // ROLE NAME / BADGE HELPERS
    // ------------------------------------------------------------------

    public static function getRoleName(int $levelId): string
    {
        return self::$ROLE_NAME[$levelId] ?? 'User';
    }

    public static function getRoleBadgeClass(int $levelId): string
    {
        return self::$ROLE_BADGE[$levelId] ?? 'bg-accent/20 text-accent';
    }

    public static function isProLevelName(int $levelId): bool
    {
        return self::getRoleName($levelId) === 'User PRO';
    }

    // ------------------------------------------------------------------
    // LEGACY STRING CONVERSION (backward compatibility)
    // ------------------------------------------------------------------

    public static function levelFromRoleString(?string $role): int
    {
        if ($role === null) {
            return self::LEVEL_USER;
        }
        return self::$ROLE_STRING_TO_LEVEL[strtolower($role)] ?? self::LEVEL_USER;
    }

    public static function roleStringFromLevel(int $levelId): string
    {
        return self::$LEVEL_TO_ROLE_STRING[$levelId] ?? 'user';
    }

    /**
     * Normalisasi session role (bisa 'role' atau 'userRole') ke level_id.
     * Jika session punya level_id valid, itu yang dipakai (sumber utama).
     * Parameter $session adalah instance CodeIgniter\Session\SessionInterface.
     */
    public static function resolveLevelId($session): int
    {
        $levelId = $session->get('level_id') ?? null;
        if (is_numeric($levelId) && isset(self::$DASHBOARD_SEGMENT[(int) $levelId])) {
            return (int) $levelId;
        }
        $role = $session->get('userRole') ?? $session->get('role') ?? null;
        return self::levelFromRoleString($role);
    }

    // ------------------------------------------------------------------
    // PERMISSION / ACCESS HELPERS
    // ------------------------------------------------------------------

    /** Level admin (5,6,7) - punya akses panel admin. */
    public static function isAdminLevel(int $levelId): bool
    {
        return in_array($levelId, [
            self::LEVEL_ADMIN,
            self::LEVEL_ACCOUNTING,
            self::LEVEL_SUPER_ADMIN,
        ], true);
    }

    /** Level staff (wpa/cwpa/admin/dll) - bypass batasan user biasa. */
    public static function isStaffLevel(int $levelId): bool
    {
        return in_array($levelId, [
            self::LEVEL_CWPA,
            self::LEVEL_WPA,
            self::LEVEL_ADMIN,
            self::LEVEL_ACCOUNTING,
            self::LEVEL_SUPER_ADMIN,
            self::LEVEL_ADMIN_WPA,
            self::LEVEL_PARTNERSHIP,
            self::LEVEL_CEO,
        ], true);
    }

    /** PRO ke atas (>=2) - akses fitur berbayar/terbatas. */
    public static function isProLevel(int $levelId): bool
    {
        return $levelId >= self::LEVEL_PRO;
    }

    /** Hanya role CEO yang boleh mengambil keputusan eksekutif. */
    public static function isCeoLevel(int $levelId): bool
    {
        return $levelId === self::LEVEL_CEO;
    }

    /** Level yang boleh akses fitur Advokasi tanpa beli (staff + pro). */
    public static function canAccessAdvokasi(int $levelId): bool
    {
        return self::isStaffLevel($levelId) || self::isProLevel($levelId);
    }

    /** Semua level_id admin valid (untuk filter route). */
    public static function getAdminLevels(): array
    {
        return [self::LEVEL_ADMIN, self::LEVEL_ACCOUNTING, self::LEVEL_SUPER_ADMIN];
    }

    /** Map role string filter (dari query ?role=) ke array level_id. Return null bila tidak ada filter. */
    public static function getLevelIdsByRoleFilter(?string $role): ?array
    {
        switch ($role) {
            case 'user_pro':
                return [self::LEVEL_PRO];
            case 'admin':
                return [self::LEVEL_ADMIN, self::LEVEL_ACCOUNTING, self::LEVEL_SUPER_ADMIN];
            case 'wpa':
                return [self::LEVEL_WPA];
            case 'cwpa':
                return [self::LEVEL_CWPA];
            case 'admin-wpa':
                return [self::LEVEL_ADMIN_WPA];
            case 'admin-partnership':
                return [self::LEVEL_PARTNERSHIP];
            case 'accounting':
                return [self::LEVEL_ACCOUNTING];
            case 'ceo':
                return [self::LEVEL_CEO];
            case 'user':
                return [self::LEVEL_USER, self::LEVEL_PRO];
            default:
                return null;
        }
    }
}
