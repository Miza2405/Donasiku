<?php

$koneksi = mysqli_connect('localhost', 'root','','nama_database' )
if ($koneksi) {
    echo "Koneksi bershasil";
} else ($koneksi) {
    echo "Koneksi gagal"
}
?>