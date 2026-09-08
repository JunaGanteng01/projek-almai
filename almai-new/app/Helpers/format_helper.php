<?php

if (!function_exists('format_rupiah')) {
    function format_rupiah($number, $prefix = 'Rp ')
    {
        return $prefix . number_format($number, 0, ',', '.');
    }
}

if (!function_exists('format_number_short')) {
    function format_number_short($number)
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return $number;
    }
}
