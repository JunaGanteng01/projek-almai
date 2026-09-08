<?php

namespace App\Helpers;

class BirthdayHelper
{
    /**
     * Parses a string like "Jakarta, 13 Juni 1982" or "16-12-1979"
     * returns array ['day' => int, 'month' => int] or null
     */
    public static function parseBirthday(string $data): ?array
    {
        $data = trim($data);
        if (empty($data)) return null;

        // Indonesian Month Mapping
        $months = [
            'januari' => 1, 'jan' => 1,
            'februari' => 2, 'feb' => 2,
            'maret' => 3, 'mar' => 3,
            'april' => 4, 'apr' => 4,
            'mei' => 5,
            'juni' => 6, 'jun' => 6,
            'juli' => 7, 'jul' => 7,
            'agustus' => 8, 'agt' => 8, 'agu' => 8,
            'september' => 9, 'sep' => 9,
            'oktober' => 10, 'okt' => 10,
            'november' => 11, 'nov' => 11,
            'desember' => 12, 'des' => 12
        ];

        // Try patterns
        
        // 1. "13 Juni 1982" or "13-06-1982" or "13/06/1982"
        // Regex: (day) (separator/space) (month name or number) (separator/space) (year)
        
        // Pattern for names: "13 Juni 1982"
        if (preg_match('/(\d{1,2})\s+([a-zA-Z]+)\s+(\d{4})/', $data, $matches)) {
            $day = (int)$matches[1];
            $monthName = strtolower($matches[2]);
            if (isset($months[$monthName])) {
                return ['day' => $day, 'month' => $months[$monthName]];
            }
        }

        // Pattern for digits: "16-12-1979" or "16/12/1979"
        if (preg_match('/(\d{1,2})[-|\/](\d{1,2})[-|\/](\d{4})/', $data, $matches)) {
            return ['day' => (int)$matches[1], 'month' => (int)$matches[2]];
        }

        // Pattern for "Place, 13 Juni 1982"
        if (preg_match('/,\s*(\d{1,2})\s+([a-zA-Z]+)\s+(\d{4})/', $data, $matches)) {
          $day = (int)$matches[1];
          $monthName = strtolower($matches[2]);
          if (isset($months[$monthName])) {
              return ['day' => $day, 'month' => $months[$monthName]];
          }
        }

        // Fallback: search for a day-month combo anywhere in the string
        // Search for Month names
        foreach ($months as $name => $num) {
            if (stripos($data, $name) !== false) {
                // Find digits near this month name
                if (preg_match('/(\d{1,2})\s+' . preg_quote($name, '/') . '/i', $data, $matches)) {
                    return ['day' => (int)$matches[1], 'month' => $num];
                }
                if (preg_match('/' . preg_quote($name, '/') . '\s+(\d{1,2})/i', $data, $matches)) {
                    return ['day' => (int)$matches[1], 'month' => $num];
                }
            }
        }

        return null;
    }

    /**
     * Checks if a user is having a birthday today
     */
    public static function isBirthdayToday(string $data): bool
    {
        $parsed = self::parseBirthday($data);
        if (!$parsed) return false;

        $todayDay = (int)date('j');
        $todayMonth = (int)date('n');

        return ($parsed['day'] === $todayDay && $parsed['month'] === $todayMonth);
    }
}
