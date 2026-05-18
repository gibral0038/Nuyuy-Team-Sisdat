<?php
$server = "localhost";
$user = "root";
$password = "";

$db_penjualan = "db_penjualan";
$db_gudang    = "db_gudang";

// Koneksi ke Database Penjualan
$conn_penjualan = mysqli_connect($server, $user, $password, $db_penjualan);

// Koneksi ke Database Gudang
$conn_gudang = mysqli_connect($server, $user, $password, $db_gudang);

// Cek apakah kedua koneksi berhasil
if( !$conn_penjualan || !$conn_gudang ){
    die("Gagal terhubung dengan database: " . mysqli_connect_error());
}
?>