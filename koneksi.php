<?php
$servername = "sql210.infinityfree.com";
$username = "if0_42242004";
$password = "GK7z0yaMe9Vt";
$database = "if0_42242004_db_donasiku";

    $koneksi = new mysqli($servername, $username, $password, $database);

    if ($koneksi->connect_error) {
        die("Gagal: " . $koneksi->connect_error);
    }

    // Tampilkan pesan hanya saat file diakses langsung
    if (basename($_SERVER['PHP_SELF']) == 'koneksi.php') {
        echo "Berhasil terhubung ke database donasiku";
    }

    $koneksi->set_charset("utf8mb4");
?>