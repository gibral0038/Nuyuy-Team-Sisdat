<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'supplier') {
    die("Akses Ditolak.");
}

if (!isset($_POST['tambah_stok'])) {
    die("Akses Ditolak.");
}

$id_produk = (int)($_POST['id_produk'] ?? 0);
$tambah_stok = (int)($_POST['jumlah_tambah'] ?? 0);

if ($tambah_stok <= 0) {
    die("Jumlah tambah stok harus > 0");
}

$id_supplier = (int)$_SESSION['id_pengguna'];

mysqli_begin_transaction($conn_gudang);
try {
    // Validasi produk milik supplier
    $sqlVal = "SELECT id_produk FROM produk WHERE id_produk = '$id_produk' AND id_supplier = '$id_supplier' LIMIT 1";
    $qVal = mysqli_query($conn_gudang, $sqlVal);
    if (!$qVal || mysqli_num_rows($qVal) < 1) {
        throw new Exception('Produk tidak ditemukan / tidak boleh ditambah stok.');
    }

    // Increment stok di gudang
    $sqlInc = "UPDATE gudang
               SET stok_sekarang = stok_sekarang + '$tambah_stok',
                   tanggal_update = CURDATE()
               WHERE id_produk = '$id_produk'";
    $qInc = mysqli_query($conn_gudang, $sqlInc);
    if (!$qInc) {
        throw new Exception('Gagal menambah stok.');
    }

    mysqli_commit($conn_gudang);
    header("Location: ../frontend/supplier-page.php?pesan=tambah_stok_sukses");
    exit();
} catch (Exception $e) {
    mysqli_rollback($conn_gudang);
    header("Location: ../frontend/supplier-page.php?pesan=tambah_stok_gagal&err=" . urlencode($e->getMessage()));
    exit();
}
?>
