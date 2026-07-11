<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAMA', 'dbcuti');

// Koneksi utama menggunakan MySQLi
$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAMA);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

function koneksi_buka() {
    global $koneksi;
    return $koneksi;
}

function koneksi_tutup() {
    global $koneksi;
    mysqli_close($koneksi);
}
?>