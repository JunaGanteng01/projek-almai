<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

$mysqli = @new mysqli("localhost", "root", "", "almai");
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error);
}

$file = 'd:\\ALMAI_PROJECT_MASTER\\02-database\\almai-new-agustus2026.sql';
if (!file_exists($file)) {
    die("File SQL tidak ditemukan di $file");
}

echo "<h3>Memulai Import Database...</h3>";
flush();

$handle = fopen($file, "r");
if (!$handle) {
    die("Gagal membuka file SQL.");
}

$query = '';
$count = 0;
$errors = 0;

$mysqli->query("SET FOREIGN_KEY_CHECKS = 0;");

while (($line = fgets($handle)) !== false) {
    // Abaikan komentar dan baris kosong
    if (substr(trim($line), 0, 2) == '--' || trim($line) == '') {
        continue;
    }
    
    $query .= $line;
    
    // Jika akhir dari statement SQL (diakhiri dengan semicolon)
    if (substr(trim($line), -1) == ';') {
        if (!$mysqli->query($query)) {
            echo "<p style='color:red;'><b>Error pada query:</b> " . htmlspecialchars($mysqli->error) . "<br>";
            echo "<b>Query:</b> <pre>" . htmlspecialchars(substr($query, 0, 300)) . "...</pre></p>";
            $errors++;
            if ($errors > 10) {
                echo "<p style='color:red;'>Terlalu banyak error. Import dihentikan.</p>";
                break;
            }
        }
        $query = '';
        $count++;
    }
}

$mysqli->query("SET FOREIGN_KEY_CHECKS = 1;");
fclose($handle);

echo "<p>Proses selesai. Berhasil menjalankan $count query. Total error: $errors.</p>";
