<?php

$koneksi = mysqli_connect('localhost', 'root','','dbdonasiku' )
if ($koneksi) {
    echo "Koneksi bershasil";
} else ($koneksi) {
    echo "Koneksi gagal";
}
?>