<?php
$file = 'd:\\ALMAI_PROJECT_MASTER\\02-database\\almai-new-agustus2026.sql';
if (!file_exists($file)) {
    die("File SQL tidak ditemukan!");
}
$handle = fopen($file, "r");
if ($handle) {
    echo "<pre>";
    $tables = ['pelatihan_simulasi', 'signals', 'konsultasi', 'expert_advisor', 'kegiatan_lainnya'];
    $current_table = '';
    $in_create = false;
    $line_num = 0;
    while (($line = fgets($handle)) !== false) {
        $line_num++;
        foreach ($tables as $table) {
            if (preg_match("/CREATE TABLE\s+`$table`/i", $line)) {
                $current_table = $table;
                $in_create = true;
                echo "-- Table: $table (Line $line_num)\n";
            }
        }
        if ($in_create) {
            echo $line;
            if (trim($line) == ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;' || trim($line) == ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;' || (substr(trim($line), -2) == ');' && strpos($line, 'CREATE') === false)) {
                $in_create = false;
                echo "\n\n";
            }
        }
    }
    fclose($handle);
    echo "</pre>";
} else {
    echo "Gagal membuka file SQL.";
}
