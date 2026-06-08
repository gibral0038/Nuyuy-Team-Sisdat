<?php
session_start();
include("koneksi.php");

// Proteksi Keamanan: Pastikan yang mengakses file ini murni customer yang sudah login
if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'customer') {
    die("Akses Ilegal: Anda harus login sebagai customer.");
}

// Cek apakah tombol checkout sudah diklik
if(isset($_POST['checkout'])){

    // Ambil data dari form
    $id_produk = $_POST['id_produk'];
    $jumlah_beli = (int)$_POST['jumlah_beli'];
    $total_bayar = $_POST['total_bayar']; // Pastikan tag input name di form-pesan.php adalah 'total_bayar'
    
    // Ambil ID pengguna asli yang sedang login dari Session
    $id_customer = $_SESSION['id_pengguna']; 

    // Mulai transaksi untuk menjaga konsistensi 2 Database
    mysqli_begin_transaction($conn_penjualan);
    mysqli_begin_transaction($conn_gudang);

    try {
        // TAHAP 0: VALIDASI KEAMANAN STOK
        $id_produk = (int)$id_produk;
        $jumlah_beli = (int)$jumlah_beli;
        if ($id_produk <= 0 || $jumlah_beli <= 0) {
            throw new Exception("Input tidak valid.");
        }

        // Mengecek ketersediaan stok di tabel gudang sebelum memproses apapun
        $cekStok = mysqli_query($conn_gudang, "SELECT stok_sekarang FROM gudang WHERE id_produk = '$id_produk' FOR UPDATE");
        $dataStok = mysqli_fetch_assoc($cekStok);

        if(!$dataStok || (int)$dataStok['stok_sekarang'] < $jumlah_beli) {
            throw new Exception("Jumlah beli tidak valid atau stok tidak mencukupi.");
        }




        // TAHAP 1: DATABASE PENJUALAN (mengikuti schema backend/penjualan.sql)
        // 1) Pesanan (status awal: selesai - supaya langsung terpotong stok)
        $sql1 = "INSERT INTO pesanan (id_pengguna, status_pesanan) VALUES ('$id_customer', 'pending')";
        mysqli_query($conn_penjualan, $sql1);
        $id_pesanan_baru = mysqli_insert_id($conn_penjualan);

        // 2) Detail pesanan (harga_total = total_bayar)
        $sql2 = "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga_total) VALUES ('$id_pesanan_baru', '$id_produk', '$jumlah_beli', '$total_bayar')";
        mysqli_query($conn_penjualan, $sql2);

        // 3) Pembayaran (metode_pembayaran default)
        $sql3 = "INSERT INTO pembayaran (id_pesanan, metode_pembayaran, jumlah_pembayaran) VALUES ('$id_pesanan_baru', 'cash', '$total_bayar')";
        mysqli_query($conn_penjualan, $sql3);


        // TAHAP 2: SINKRONISASI KE DATABASE GUDANG
        // 4. Potong Stok Gudang ($stok_sekarang = $stok_sekarang - $jumlah_dipesan)
        $sql4 = "UPDATE gudang SET stok_sekarang = stok_sekarang - $jumlah_beli WHERE id_produk = '$id_produk'";
        mysqli_query($conn_gudang, $sql4);

        // 5. Catat ke Laporan Penjualan
        $sql5 = "INSERT INTO laporan_penjualan (id_produk, jumlah_terjual, tanggal) VALUES ('$id_produk', '$jumlah_beli', NOW())";
        mysqli_query($conn_gudang, $sql5);

        // 6. Update Best Seller (top terjual)
        // Schema: best_seller(id_produk, jumlah_terjual, periode)
        // Karena periode di skema enum butuh nilai bulan/tahun, kita gunakan 'tahun' default.
        // Jika baris belum ada => INSERT
        // Jika sudah ada => UPDATE increment
        $sqlBestCek = "SELECT id_best_seller FROM best_seller WHERE id_produk = '$id_produk' LIMIT 1";
        $qBestCek = mysqli_query($conn_gudang, $sqlBestCek);

        if ($qBestCek && mysqli_num_rows($qBestCek) > 0) {
            $sqlBestUpd = "UPDATE best_seller
                            SET jumlah_terjual = jumlah_terjual + '$jumlah_beli'
                            WHERE id_produk = '$id_produk'";
            mysqli_query($conn_gudang, $sqlBestUpd);
        } else {
            $sqlBestIns = "INSERT INTO best_seller (id_produk, jumlah_terjual, periode)
                            VALUES ('$id_produk', '$jumlah_beli', 'tahun')";
            mysqli_query($conn_gudang, $sqlBestIns);
        }


        // Jika semua 5 tahap sukses dilalui tanpa error, commit/permanenkan perubahan di kedua DB
        mysqli_commit($conn_penjualan);
        mysqli_commit($conn_gudang);

        // Alihkan kembali ke dashboard customer dengan pesan sukses
        header('Location: ../frontend/form-pesan.php?pesan=berhasil');

    } catch (Exception $e) {
        // Jika ada satu saja tahap yang gagal atau manipulasi stok, BATALKAN SEMUANYA
        mysqli_rollback($conn_penjualan);
        mysqli_rollback($conn_gudang);
        
        // Alihkan kembali dengan pesan error
        header('Location: ../frontend/form-pesan.php?pesan=gagal');
    }

} else {
    die("Akses dilarang...");
}
?>