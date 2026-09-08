<?php
$file = 'd:\\ALMAI_PROJECT_MASTER\\02-database\\almai-new-agustus2026.sql';
if (!file_exists($file)) {
    die("File SQL tidak ditemukan!");
}
$handle = fopen($file, "r");
if ($handle) {
    echo "<h3>Create Table:</h3><pre>";
    $line_num = 0;
    while (($line = fgets($handle)) !== false) {
        $line_num++;
        if ($line_num >= 303220 && $line_num <= 303260) {
            echo "Line $line_num: " . htmlspecialchars($line);
        }
        if ($line_num > 303260) break;
    }
    fclose($handle);
    echo "</pre>";
} else {
    echo "Gagal membuka file SQL.";
}
