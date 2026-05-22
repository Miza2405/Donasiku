<?php

$koneksi = mysqli_connect('localhost', 'root','','dbdonasiku' );

if ($koneksi) {
    echo "Koneksi bershasil";
} else {
    echo "Koneksi gagal";
}
?>