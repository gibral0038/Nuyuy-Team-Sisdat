<?php
include("koneksi.php");

// Cek apakah tombol checkout sudah diklik
if(isset($_POST['checkout'])){

    // Ambil data dari form
    $id_produk = $_POST['id_produk'];
    $jumlah_beli = $_POST['jumlah_beli'];
    $total_bayar = $_POST['total_bayar'];
    $user_id_simulasi = 1; // Asumsi ID pengguna yang sedang login adalah 1

    // Mulai transaksi untuk menjaga konsistensi 2 Database
    mysqli_begin_transaction($conn_penjualan);
    mysqli_begin_transaction($conn_gudang);

    try {
        // TAHAP 1: DATABASE PENJUALAN
        // 1. Masukkan data ke tabel pesanan dengan status langsung "Selesai" karena sudah bayar
        $sql1 = "INSERT INTO pesanan (user_id, status) VALUES ('$user_id_simulasi', 'Selesai')";
        mysqli_query($conn_penjualan, $sql1);
        $id_pesanan_baru = mysqli_insert_id($conn_penjualan); // Ambil ID pesanan yang baru saja terbuat

        // 2. Masukkan data ke detail_pesanan
        $sql2 = "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah) VALUES ('$id_pesanan_baru', '$id_produk', '$jumlah_beli')";
        mysqli_query($conn_penjualan, $sql2);

        // 3. Masukkan ke tabel pembayaran
        $sql3 = "INSERT INTO pembayaran (id_pesanan, total) VALUES ('$id_pesanan_baru', '$total_bayar')";
        mysqli_query($conn_penjualan, $sql3);

        // TAHAP 2: SINKRONISASI KE DATABASE GUDANG
        // 4. Potong Stok Gudang ($stok_sekarang = $stok_sekarang - $jumlah_dipesan)
        $sql4 = "UPDATE gudang SET stok_sekarang = stok_sekarang - $jumlah_beli WHERE id_produk = '$id_produk'";
        mysqli_query($conn_gudang, $sql4);

        // 5. Catat ke Laporan Penjualan
        $sql5 = "INSERT INTO laporan_penjualan (id_produk, jumlah_terjual, tanggal) VALUES ('$id_produk', '$jumlah_beli', NOW())";
        mysqli_query($conn_gudang, $sql5);

        // Jika semua query sukses, commit/permanenkan perubahan di kedua DB
        mysqli_commit($conn_penjualan);
        mysqli_commit($conn_gudang);

        // Alihkan ke halaman status pesanan dengan status sukses (gaya Petanikode)
        header('Location: status-pesanan.php?status=sukses');

    } catch (Exception $e) {
        // Jika ada satu saja yang gagal, batalkan semuanya
        mysqli_rollback($conn_penjualan);
        mysqli_rollback($conn_gudang);
        header('Location: status-pesanan.php?status=gagal');
    }

} else {
    die("Akses dilarang...");
}
?>