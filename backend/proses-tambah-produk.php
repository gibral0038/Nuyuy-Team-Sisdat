<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'supplier') {
    die("Akses Ditolak.");
}

if (!isset($_POST['tambah_produk'])) {
    die("Akses Ditolak.");
}

$nama_produk = $_POST['nama_produk'] ?? '';
$deskripsi_produk = $_POST['deskripsi_produk'] ?? '';
$harga_produk = (int)($_POST['harga_produk'] ?? 0);
$stok_awal = (int)($_POST['stok_awal'] ?? 0);

$id_supplier = (int)$_SESSION['id_pengguna'];

mysqli_begin_transaction($conn_gudang);
try {
    $sqlProduk = "INSERT INTO produk (id_supplier, nama_produk, deskripsi_produk, harga_produk, stok_produk)
                  VALUES ('$id_supplier', '$nama_produk', '$deskripsi_produk', '$harga_produk', '$stok_awal')";
    $q1 = mysqli_query($conn_gudang, $sqlProduk);
    if (!$q1) {
        throw new Exception('Gagal menambah produk.');
    }

    $id_produk_baru = mysqli_insert_id($conn_gudang);

    $sqlGudang = "INSERT INTO gudang (id_produk, stok_awal, stok_sekarang, tanggal_update)
                   VALUES ('$id_produk_baru', '$stok_awal', '$stok_awal', CURDATE())";
    $q2 = mysqli_query($conn_gudang, $sqlGudang);
    if (!$q2) {
        throw new Exception('Gagal menambah stok awal.');
    }

    mysqli_commit($conn_gudang);
    header("Location: ../frontend/supplier-page.php?pesan=tambah_produk_sukses");
    exit();
} catch (Exception $e) {
    mysqli_rollback($conn_gudang);
    header("Location: ../frontend/supplier-page.php?pesan=tambah_produk_gagal&err=" . urlencode($e->getMessage()));
    exit();
}
?>
