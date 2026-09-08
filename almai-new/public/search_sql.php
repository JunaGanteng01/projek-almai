<?php
$file = 'd:\\ALMAI_PROJECT_MASTER\\02-database\\almai-new-agustus2026.sql';
if (!file_exists($file)) {
    die("File SQL tidak ditemukan!");
}
$handle = fopen($file, "r");
if ($handle) {
    echo "<h3>Matching Lines:</h3><pre>";
    $line_num = 0;
    while (($line = fgets($handle)) !== false) {
        $line_num++;
        if (stripos($line, 'pelatihan_simulasi') !== false) {
            echo "Line $line_num: " . htmlspecialchars(substr($line, 0, 500)) . "\n";
        }
    }
    fclose($handle);
    echo "</pre>";
} else {
    echo "Gagal membuka file SQL.";
}
