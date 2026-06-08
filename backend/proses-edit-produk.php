<?php
session_start();
include("koneksi.php");

// Edit produk milik supplier aktif.
// - stok: REPLACE ke gudang.stok_sekarang
if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'supplier') {
    die("Akses Ditolak.");
}

if (!isset($_POST['update_produk'])) {
    die("Akses Ditolak.");
}

$id_produk = (int)($_POST['id_produk'] ?? 0);
$nama_produk = $_POST['nama_produk'] ?? '';
$deskripsi_produk = $_POST['deskripsi_produk'] ?? '';
$harga_produk = (int)($_POST['harga_produk'] ?? 0);
$stok_produk_baru = (int)($_POST['stok_produk_baru'] ?? 0);

$id_supplier = (int)$_SESSION['id_pengguna'];

mysqli_begin_transaction($conn_gudang);
try {
    // Update detail produk
    $sql1 = "UPDATE produk SET
                nama_produk = '$nama_produk',
                deskripsi_produk = '$deskripsi_produk',
                harga_produk = '$harga_produk'
             WHERE id_produk = '$id_produk' AND id_supplier = '$id_supplier'";

    $q1 = mysqli_query($conn_gudang, $sql1);
    if (!$q1 || mysqli_affected_rows($conn_gudang) < 1) {
        throw new Exception('Produk tidak ditemukan / tidak boleh diubah.');
    }

    // Replace stok di gudang
    $sql2 = "UPDATE gudang SET
                stok_sekarang = '$stok_produk_baru',
                tanggal_update = CURDATE()
             WHERE id_produk = '$id_produk'";

    $q2 = mysqli_query($conn_gudang, $sql2);
    if (!$q2) {
        throw new Exception('Gagal update stok.');
    }

    mysqli_commit($conn_gudang);
    header("Location: ../frontend/supplier-page.php?pesan=edit_sukses");
    exit();
} catch (Exception $e) {
    mysqli_rollback($conn_gudang);
    header("Location: ../frontend/supplier-page.php?pesan=edit_gagal&err=" . urlencode($e->getMessage()));
    exit();
}
?>
