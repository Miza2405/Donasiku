<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_donasiku";

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