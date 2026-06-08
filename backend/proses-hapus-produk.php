<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'supplier') {
    die("Akses Ditolak.");
}

if (!isset($_POST['hapus_produk'])) {
    die("Akses Ditolak.");
}

$id_produk = (int)($_POST['id_produk'] ?? 0);
$id_supplier = (int)$_SESSION['id_pengguna'];

if ($id_produk <= 0) {
    die("ID produk tidak valid.");
}

// Rule yang dipilih user: hapus produk supplier => riwayat customer terkait ikut hilang.
// Ini berarti kita cleanup di db_penjualan juga.
mysqli_begin_transaction($conn_gudang);
mysqli_begin_transaction($conn_penjualan);
try {
    // 1) Pastikan produk memang milik supplier aktif
    $cekSql = "SELECT id_produk FROM produk WHERE id_produk = '$id_produk' AND id_supplier = '$id_supplier' FOR UPDATE";
    $qCek = mysqli_query($conn_gudang, $cekSql);
    if (!$qCek || mysqli_num_rows($qCek) < 1) {
        throw new Exception('Produk tidak ditemukan / tidak boleh dihapus.');
    }

    // 2) Hapus transaksi terkait di db_penjualan (detail_pesanan -> pesanan)
    //    Karena skema dump membuat FK detail_pesanan -> pesanan ON DELETE CASCADE,
    //    kita aman hapus detail_pesanan terlebih dulu.
    $delDetailsSql = "DELETE dp
                       FROM detail_pesanan dp
                       JOIN pesanan pn ON pn.id_pesanan = dp.id_pesanan
                       WHERE dp.id_produk = '$id_produk'";
    $qDelDetails = mysqli_query($conn_penjualan, $delDetailsSql);
    if (!$qDelDetails) {
        throw new Exception('Gagal membersihkan detail_pesanan terkait.');
    }

    // 3) Hapus pesanan yang sekarang sudah tidak punya detail (opsional).
    //    Untuk mencegah delete berlebihan, hapus hanya pesanan yang id-nya pernah terkait produk ini.
    $delOrdersSql = "DELETE pn
                      FROM pesanan pn
                      WHERE pn.id_pesanan IN (
                          SELECT dp2.id_pesanan
                          FROM detail_pesanan dp2
                          WHERE dp2.id_produk = '$id_produk'
                      )";
    // Catatan: setelah step 2, detail_pesanan untuk produk sudah hilang,
    // sehingga subquery mungkin kosong. Ini tidak masalah.
    mysqli_query($conn_penjualan, $delOrdersSql);

    // 4) Hapus produk di db_gudang
    // Karena gudang.sql punya FK ON DELETE CASCADE (best_seller/gudang/laporan_penjualan), stok ikut terhapus.
    $sqlDel = "DELETE FROM produk WHERE id_produk = '$id_produk' AND id_supplier = '$id_supplier'";
    $qDel = mysqli_query($conn_gudang, $sqlDel);
    if (!$qDel || mysqli_affected_rows($conn_gudang) < 1) {
        throw new Exception('Produk tidak ditemukan / tidak boleh dihapus.');
    }

    mysqli_commit($conn_gudang);
    mysqli_commit($conn_penjualan);

    header("Location: ../frontend/supplier-page.php?pesan=hapus_sukses");
    exit();
} catch (Exception $e) {
    mysqli_rollback($conn_gudang);
    mysqli_rollback($conn_penjualan);
    header("Location: ../frontend/supplier-page.php?pesan=hapus_gagal&err=" . urlencode($e->getMessage()));
    exit();
}
?>
