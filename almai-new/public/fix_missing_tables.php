<?php
set_time_limit(120);
ini_set('memory_limit', '256M');

$mysqli = @new mysqli("localhost", "root", "", "almai");
if ($mysqli->connect_error) {
    die("<p style='color:red;'>Connect Error: " . $mysqli->connect_error . "</p>");
}
$mysqli->set_charset("utf8mb4");

$queries = [];

// =====================================================
// 1. expert_advisor
// =====================================================
$queries['expert_advisor'] = "CREATE TABLE IF NOT EXISTS `expert_advisor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_layanan` varchar(255) NOT NULL,
  `penjelasan_layanan` text DEFAULT NULL,
  `jml_klien` int(11) NOT NULL DEFAULT 0,
  `produk` varchar(255) DEFAULT NULL,
  `wpa_id` int(11) NOT NULL,
  `winning_rate` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `cwpa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_qr` varchar(100) DEFAULT NULL,
  `link_absensi` varchar(255) DEFAULT NULL,
  `expired_link_kode_qr` datetime DEFAULT NULL,
  `created_by_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_wpa_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by_cwpa_id` int(10) UNSIGNED DEFAULT NULL,
  `format_notif_wa` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

// =====================================================
// 2. kegiatan_lainnya
// =====================================================
$queries['kegiatan_lainnya'] = "CREATE TABLE IF NOT EXISTS `kegiatan_lainnya` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kegiatan` varchar(255) NOT NULL,
  `jml_klien` int(11) NOT NULL DEFAULT 0,
  `jml_nasihat` int(11) NOT NULL DEFAULT 0,
  `produk` varchar(255) DEFAULT NULL,
  `wpa_id` int(11) NOT NULL,
  `media` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `cwpa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_qr` varchar(100) DEFAULT NULL,
  `link_absensi` varchar(255) DEFAULT NULL,
  `expired_link_kode_qr` datetime DEFAULT NULL,
  `created_by_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_wpa_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by_cwpa_id` int(10) UNSIGNED DEFAULT NULL,
  `format_notif_wa` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

// =====================================================
// 3. konsultasi
// =====================================================
$queries['konsultasi'] = "CREATE TABLE IF NOT EXISTS `konsultasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_klien` varchar(255) DEFAULT NULL,
  `jml_nasihat` int(11) NOT NULL DEFAULT 0,
  `produk` varchar(255) DEFAULT NULL,
  `wpa_id` int(11) NOT NULL,
  `media` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `cwpa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_qr` varchar(100) DEFAULT NULL,
  `link_absensi` varchar(255) DEFAULT NULL,
  `expired_link_kode_qr` datetime DEFAULT NULL,
  `created_by_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_wpa_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by_cwpa_id` int(10) UNSIGNED DEFAULT NULL,
  `format_notif_wa` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

// =====================================================
// 4. pelatihan_simulasi
// =====================================================
$queries['pelatihan_simulasi'] = "CREATE TABLE IF NOT EXISTS `pelatihan_simulasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `jml_peserta` int(11) NOT NULL DEFAULT 0,
  `produk` varchar(255) DEFAULT NULL,
  `wpa_id` int(11) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `topik` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `cwpa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_qr` varchar(100) DEFAULT NULL,
  `link_absensi` varchar(255) DEFAULT NULL,
  `expired_link_kode_qr` datetime DEFAULT NULL,
  `created_by_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_wpa_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by_cwpa_id` int(10) UNSIGNED DEFAULT NULL,
  `format_notif_wa` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

// =====================================================
// 5. signals
// =====================================================
$queries['signals'] = "CREATE TABLE IF NOT EXISTS `signals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jml_peserta` int(11) NOT NULL DEFAULT 0,
  `jml_nasihat` int(11) NOT NULL DEFAULT 0,
  `produk` varchar(255) DEFAULT NULL,
  `wpa_id` int(11) NOT NULL,
  `media` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `cwpa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_qr` varchar(100) DEFAULT NULL,
  `link_absensi` varchar(255) DEFAULT NULL,
  `expired_link_kode_qr` datetime DEFAULT NULL,
  `created_by_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_wpa_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by_cwpa_id` int(10) UNSIGNED DEFAULT NULL,
  `format_notif_wa` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `chart_capture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

echo "<html><head><style>body{font-family:monospace;padding:20px;background:#1a1a1a;color:#eee;}</style></head><body>";
echo "<h2>🔧 Memperbaiki Database Lokal ALMAI</h2>";
echo "<table border='1' cellpadding='8' style='width:100%;border-collapse:collapse;'>";
echo "<tr style='background:#333'><th>Tabel</th><th>Status</th></tr>";

foreach ($queries as $table => $sql) {
    $result = $mysqli->query($sql);
    if ($result) {
        $check = $mysqli->query("SELECT COUNT(*) as c FROM `$table`");
        $row = $check->fetch_assoc();
        echo "<tr><td><b>$table</b></td><td style='color:#4CAF50;'>✅ BERHASIL (". $row['c'] ." baris)</td></tr>";
    } else {
        echo "<tr><td><b>$table</b></td><td style='color:#f44;'>❌ ERROR: " . htmlspecialchars($mysqli->error) . "</td></tr>";
    }
}

echo "</table>";
echo "<br><p style='color:#8f8'>✅ Selesai! Silakan refresh halaman <a href='http://localhost:8080/superadmin/absensi' style='color:#66f;'>superadmin/absensi</a></p>";
echo "</body></html>";
$mysqli->close();
