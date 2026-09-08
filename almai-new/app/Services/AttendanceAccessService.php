<?php

namespace App\Services;

use App\Models\LevelModel;

class AttendanceAccessService
{
    public const AUDIENCE_PUBLIC = 'public';
    public const AUDIENCE_CWPA_WPA = 'cwpa_wpa';
    public const ROLES_CWPA_WPA = 'cwpa,wpa';

    public static function rolesFromAudience(?string $audience): ?string
    {
        return $audience === self::AUDIENCE_CWPA_WPA ? self::ROLES_CWPA_WPA : null;
    }

    public static function isRestricted(array $activity): bool
    {
        return trim((string) ($activity['registration_roles'] ?? '')) !== '';
    }

    public static function canCheckIn(array $activity, ?int $levelId): bool
    {
        if (!self::isRestricted($activity)) {
            return true;
        }

        return $levelId !== null && in_array($levelId, [
            LevelModel::LEVEL_CWPA,
            LevelModel::LEVEL_WPA,
        ], true);
    }
}
